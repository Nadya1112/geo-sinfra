---
name: laravel-best-practice
description: Apply Laravel best practices when writing or refactoring code.
---
# Laravel Best Practice Skill

When writing Laravel code, enforce the following best practices:
- **Fat Models, Skinny Controllers**: Keep business logic out of controllers. Use Service classes or Action classes for complex logic.
- **Form Requests**: Use FormRequest classes for validation and authorization instead of validating in controllers.
- **Eloquent Optimization**: Prevent N+1 queries by using eager loading (`with()`).
- **Dependency Injection**: Favor dependency injection over facades when appropriate for better testability.
- **Route Caching/Config Caching**: Structure routes and configs so they can be cached effectively.
- **Single Responsibility Principle**: Ensure each class handles only one responsibility.
- **Resource Controllers**: Use resource controllers for standard CRUD operations.
- **Response Formatting**: Return consistent JSON responses for APIs (e.g., using API Resources).
