# [Pano](https://simcript.github.io/pano/)
## PHP Nano Framework

Pano is a **nano(very small) framework** designed around a single idea:

> **Define clear execution contracts, then provide simple default implementations.**

The framework is intentionally minimal and non-opinionated. It gives developers **structure without control**, and **contracts without restriction**.

---

## Design Philosophy

Pano is built on these principles:

* Absolute simplicity
* No Composer dependency
* No hidden magic
* Full developer control
* Clear and explicit execution flow

Pano deliberately **does not provide**:

* ❌ MVC controllers
* ❌ A service container
* ❌ Global middleware

Instead, it provides:

* ✅ Execution contracts (Core)
* ✅ Stable default behavior (Foundation)
* ✅ Module-centric architecture

---

## Core vs Foundation

Pano separates **contracts** from **behavior**.

### Core

The **Core** contains only **abstract base classes**.

These classes:

* Define execution flow
* Define method signatures
* Define responsibilities
* Act as **contracts**, not implementations

All Core classes:

* Are **abstract**
* Use the `Base` prefix
* Are safe and intended to be extended

> Core classes describe *how the system works*, not *what it does*.

### Foundation

The **Foundation** contains **final, concrete implementations** built on top of Core contracts.

These classes:

* Implement Core behavior
* Provide sensible defaults
* Are production-ready
* Can be replaced or ignored entirely

> Foundation exists for convenience, not enforcement.

---

## Directory Structure

```text
project/
│── index.php
└── Pano/
    ├── Core/
    │   ├── BaseBoot.php
    │   ├── BaseModule.php
    │   ├── BaseRequest.php
    │   └── BaseResponse.php
    │
    └── Foundation/
        ├── Boot.php
        ├── Module.php
        └── Request.php
```

---

## Entry Point

All application execution starts from a single entry point:

```php
(new \Pano\Foundation\Boot())->run();
```

You are free to replace `Foundation\Boot` with your own implementation.

---

## BaseBoot (Core Contract)

```php
abstract class BaseBoot
{
    abstract public function run(): void;

    protected BaseRequest $request;
}
```

* `run()` defines the execution flow
* Flow cannot be broken, only customized

---

## BaseModule (Core Contract)

```php
abstract class BaseModule
{
    public function __construct(
        protected BaseRequest $request
    ) {}

    abstract protected function routes(): BaseRouter;
}
```

Modules:

* Are the **main execution units**
* Receive a Request object
* Contain all business logic

Routing, validation, auth, and responses are fully controlled by the developer.

---

## BaseRequest (Core Contract)

```php
abstract class BaseRequest
{
    protected string|array $data;
    protected array $files;
    protected array $headers;
    protected array $queries;
    protected string $method;
    protected string $query;
    protected string $url;
    protected array $segments;
    protected string $host;
}
```

Advantages:

* No direct access to superglobals
* Predictable input
* High testability

---

## BaseResponse (Core Contract)

```php
abstract class BaseResponse
{
    abstract public function send(): void;

    protected int $status = 200;
    protected array $headers = [];
    protected mixed $body = null;
}
```

* `send()` is a closed execution flow
---

## Error Handling

Error handling is intentionally simple.

Developers may:

* Catch exceptions inside Modules
* Override `handleException()` in Boot
* Introduce custom exception contracts

No global error strategy is enforced.

---

## Environment (.env)

Pano supports a very simple `.env` file:

```env
APP_NAME=Pano
APP_ENV=local
APP_KEY=Iur5UWL6KVz/2jsJTfjF+YbzAmnvejpIfYWo0fzZ8Mg=
APP_DEBUG=true
APP_URL=https://neda.tst
DOMAIN_RESOLVER=path #path or subdomain
```

The parser is minimal by design and intended for basic configuration only.

---

## Suitable Use Cases

✔ MVP projects
✔ Small APIs
✔ Internal tools
✔ Personal frameworks
✔ Learning projects

❌ Large enterprise systems
❌ Large teams with strict standards

---

## Summary

Pano is a framework that:

* Separates **contracts** from **behavior**
* Trusts developers over abstractions
* Avoids magic and global state
* Encourages clarity and ownership

> *A framework should guide execution, not control it.*

---

Built for developers who prefer **understanding to convenience** 🧠
