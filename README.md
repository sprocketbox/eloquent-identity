# Eloquent Identity
[![Latest Stable Version](https://poser.pugx.org/sprocketbox/eloquent-identity/v/stable.png)](https://packagist.org/packages/sprocketbox/eloquent-identity) 
[![Latest Unstable Version](https://poser.pugx.org/sprocketbox/eloquent-identity/v/unstable.png)](https://packagist.org/packages/sprocketbox/eloquent-identity) 
[![License](https://poser.pugx.org/sprocketbox/eloquent-identity/license.png)](https://packagist.org/packages/sprocketbox/eloquent-identity)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/sprocketbox/eloquent-identity/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/sprocketbox/eloquent-identity/?branch=master)

- **Laravel**: 7
- **PHP**: 7.4+
- **License**: MIT
- **Author**: Ollie Read 
- **Author Homepage**: https://sprocketbox.io

Eloquent identity provides a cache on top of Eloquent preventing multiple models being created for a single database row 
using the Identity Map design pattern ([P of EAA](https://martinfowler.com/eaaCatalog/identityMap.html) & [Wikipedia](https://en.wikipedia.org/wiki/Identity_map_pattern)).

#### Table of Contents

- [Installing](#installing)
- [Usage](#usage)
    - [Finding](#finding)
    - [Hydrating](#hydrating)
    - [Belongs To](#belongsto)
    - [Flushing](#flushing)
- [How does it work](#how)
- [Why?](#why)

## Installing
To install this package simply run the following command.

```
composer require sprocketbox/eloquent-identity
```

This package uses auto-discovery to register the service provider but if you'd rather do it manually, 
the service provider is:

```
Sprocketbox\Eloquent\Identity\ServiceProvider
```

There is no configuration required.

## Usage
To make use of the Eloquent identity map on your models, add the following trait.

```
Sprocketbox\Eloquent\Identity\Concerns\MapsIdentity
```

### Finding
Any calls to `find()`, `findOrFail()` or `findOrNew()` on a model that uses this trait, will skip the query
if a model has already been created using the provided id.

Calls to `findMany()` will skip any ids that have already been used, only querying ones that are not present in the cache.

The query is only skipped if there are no where clauses, joins or having statements.

If you wish to force the query, you can call `refreshIdentityMap()` on the query builder instance. If you wish to skip
the query on a builder instance where `refreshIdentityMap()` has been called, you can call `useIdentityMap()`.

### Hydrating
When the query builder attempts to create a new instance of a model using this trait, with a key that matches an already 
existing instance of the model, the existing instance will be used.

If the model is using timestamps, and the returned attributes are newer, the attributes on the existing instance will be
updated, but will retain any changes you'd previously made.

### BelongsTo
If a belongs to relationship is loaded (not belongs to many) without constraints and without `refreshIdentityMap()` being 
called, the query will skip any model instances that already exist.

### Flushing
If you wish to flush the cached models, call `flushIdentities()` on an instance of `IdentityManager`, or on the `Identity`
facade.

## How
The `IdentityManager` stores an array containing all existing model instances and their identity.

The identities for models are stored as string, created using the following class.

```
Sprocketbox\Eloquent\Identity\ModelIdentity
```

This contains a key, the model class name, and the connection name. The string version of these looks like so:

```
connection:class:key
```

## Why
It's very easy to end up with multiple versions of the same model, meaning that updates on one aren't persisted
to others.

Eloquent identity was created to reduce the number of models created, help limit unnecessary queries, and allow for consistent
model interaction. It doesn't matter where in your code you're dealing with user 1, any changes made during a request
will persist across all instances.
