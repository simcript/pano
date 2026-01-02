# [Pano](https://simcript.github.io/pano/)
## Nano Domain Framework (PHP)

Pano is a **very small, Composer-independent nano framework** designed around a single idea:

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

* ❌ A router
* ❌ MVC controllers
* ❌ A service container
* ❌ Global middleware

Instead, it provides:

* ✅ Execution contracts (Core)
* ✅ Stable default behavior (Foundation)
* ✅ Domain-centric architecture

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
    │   ├── BaseDomain.php
    │   ├── BaseRequest.php
    │   └── BaseResponse.php
    │
    └── Foundation/
        ├── Boot.php
        ├── Domain.php
        ├── Request.php
        └── JsonResponse.php
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
    final public function run(): void
    {
        try {
            $this->boot();
            $this->dispatch();
        } catch (\Throwable $e) {
            $this->handleException($e);
        }
    }

    protected function boot(): void {}
    abstract protected function dispatch(): void;

    protected function handleException(\Throwable $e): void
    {
        throw $e;
    }
}
```

* `run()` defines the execution flow
* `dispatch()` is the only required implementation point
* Flow cannot be broken, only customized

---

## BaseDomain (Core Contract)

```php
abstract class BaseDomain
{
    public function __construct(
        protected readonly BaseRequest $request
    ) {}

    final public function run(): void
    {
        $result = $this->handle();

        if ($result instanceof \Closure) {
            $result();
        }
    }

    abstract protected function handle(): mixed;
}
```

Domains:

* Are the **main execution units**
* Receive a Request object
* Contain all business logic

Routing, validation, auth, and responses are fully controlled by the developer.

---

## BaseRequest (Core Contract)

```php
abstract class BaseRequest
{
    public readonly string $method;
    public readonly string $uri;
    public readonly array $query;
    public readonly array $body;
    public readonly array $headers;
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
    protected int $status = 200;
    protected array $headers = [];

    final public function send(): void
    {
        http_response_code($this->status);

        foreach ($this->headers as $key => $value) {
            header("$key: $value");
        }

        $this->output();
    }

    abstract protected function output(): void;
}
```

* `send()` is a closed execution flow
* `output()` is the customization point

---

## Foundation Classes

Foundation provides **ready-to-use implementations**:

```php
final class JsonResponse extends BaseResponse
{
    public function __construct(private mixed $data) {}

    protected function output(): void
    {
        $this->headers['Content-Type'] = 'application/json';
        echo json_encode($this->data);
    }
}
```

Foundation classes:

* Are marked `final`
* Are safe defaults
* Can be replaced by custom implementations

---

## Error Handling

Error handling is intentionally simple.

Developers may:

* Catch exceptions inside Domains
* Override `handleException()` in Boot
* Introduce custom exception contracts

No global error strategy is enforced.

---

## Environment (.env)

Pano supports a very simple `.env` file:

```env
APP_ENV=local
APP_DEBUG=true
```

The parser is minimal by design and intended for basic configuration only.

---

## Why No Composer?

This decision is **intentional**:

* Faster execution
* Full code visibility
* No dependency graph
* Ideal for learning, MVPs, and internal tools

Future forks or versions may add Composer support.

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
