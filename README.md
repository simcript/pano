# [Pano](https://simcript.github.io/pano/) 
## Nano Domain Framework (PHP)

A **very simple, Composer-independent nano-framework** based on Domains, which does just one thing:

> **It receives the incoming Request and forwards it to a specified Domain**

Any Routing, Middleware, Validation, Response, Auth, etc., is **completely handled by the Developer** within the Domains.

The project is intentionally minimal, aiming for:

* Absolute simplicity
* No Composer dependency
* Full control for the Developer over structure
* Suitable for MVPs, internal tools, small APIs, or a personal base

---

## Design Philosophy

This framework:

* ❌ Does not have a Router
* ❌ Does not enforce MVC Controllers
* ❌ Does not include global Middleware
* ❌ Does not provide a Container

Instead:

* ✅ Provides a **Domain Entry Point**
* ✅ Provides a **clean Request Object**
* ✅ Provides a simple **Boot** for execution

Each Domain is an independent starting point and can have any internal architecture.

---

## Project Structure

```text
project/   
│── index.php
└── .env
```

---

## Entry Point (index.php)

All application entry passes through this file:

```php
(new Boot())->run();
```

---

## What is Boot?

`Boot` is responsible for:

1. Receiving the Request
2. Determining the target Domain
3. Instantiating the Domain
4. Running the Domain

Boot **does not contain any business logic**.

---

## Request Object

All incoming data is provided to the Domain via the `Request` class:

```php
class Request
{
    public readonly string $method;
    public readonly string $uri;
    public readonly array $query;
    public readonly array $body;
    public readonly array $headers;
}
```

### Advantages:

* No direct access to `$_GET`, `$_POST`, `$_SERVER`
* High testability
* Clear and predictable data

---

## What is a Domain?

A Domain is the **main execution unit** of this framework.

Each Domain:

* Is a PHP class
* Extends the `Domain` base class
* Has a `handle()` method

### Base Domain Class

```php
abstract class Domain
{
    public function __construct(
        protected readonly Request $request
    ) {}
    abstract protected function handle(): \Closure;
}
```

---

## Creating a Simple Domain

```php
namespace Domains;

class Main extends \Domain
{

    public function handle(): \Closure
    {
        return fn() => $this->info();
    }

    public function info(): void
    {
        echo 'Pano a php nano framework';
    }

}
```

Inside `handle()`, the Developer can freely implement:

* Custom Routing
* Service calls
* Authentication
* Validation
* JSON or HTML Responses

Everything is fully flexible.

---

## Selecting the Target Domain

Boot determines **which Domain to execute** based on the Request.

Simple example:

```php
$domainClass = match ($request->uri) {
    '/' => HomeDomain::class,
    '/api' => ApiDomain::class,
    default => NotFoundDomain::class,
};
```

This logic can be:

* Conditional
* Regex-based
* Config-driven
* Or fully custom

---

## Error Handling

In this version:

* Error handling is simple and developer-friendly
* For production, customization is recommended

Developers can use:

* try/catch inside Domain
* Or add wrappers in Boot

---

## .env File

Simple environment support for basic configuration:

```env
APP_ENV=local
APP_DEBUG=true
```

> The parser is simple and not meant for complex configuration.

---

## Why No Composer?

This decision is **intentional**:

* Fast execution
* Full understanding of the code
* No external dependencies
* Suitable for learning and personal use

> Future versions or forks may support Composer and PSR-4.

---

## Suitable Use Cases

✔ Internal tools
✔ Small APIs
✔ MVP projects
✔ Learning projects
✔ Personal framework base

❌ Large enterprise projects
❌ Large teams with strict standards

---

## Optional Future Enhancements

These are **optional**:

* Composer + PSR-4 support
* Independent Router
* Response Object
* Middleware Stack
* Attribute-based mapping

---

## Summary

This nano-framework is:

* Small
* Transparent
* Does not impose restrictions
* Trusts the Developer

> “A framework should not get in your way.”

---

Built for Developers who **prefer control to magic** 🧠
