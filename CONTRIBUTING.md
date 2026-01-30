# Contributing to LinkUp

Thank you for your interest in contributing to LinkUp! We welcome contributions from the community. This document provides guidelines and instructions for contributing to the project.

## Code of Conduct

- Be respectful and inclusive
- Provide constructive feedback
- Focus on ideas and improvements, not personal criticism
- Help create a welcoming environment for everyone

## Getting Started

1. **Fork the repository** on GitHub
2. **Clone your fork** locally
   ```bash
   git clone https://github.com/yourusername/social-media.git
   cd social-media
   ```
3. **Create a feature branch**
   ```bash
   git checkout -b feature/your-feature-name
   ```
4. **Set up development environment**
   ```bash
   composer install
   npm install
   cp .env.example .env
   php artisan key:generate
   php artisan migrate
   ```

## Development Workflow

### Making Changes

1. **Write descriptive commit messages**
   ```
   git commit -m "Add feature: clear description of what was changed"
   ```

2. **Follow Laravel conventions**
   - PSR-12 coding style
   - Meaningful variable and function names
   - Comprehensive comments for complex logic

3. **Test your changes**
   ```bash
   php artisan test
   ```

4. **Format your code**
   ```bash
   composer run format
   ```

### Writing Tests

- Add tests for all new features
- Update existing tests if behavior changes
- Aim for at least 80% code coverage
- Write descriptive test names

Example test structure:
```php
public function test_user_can_perform_action()
{
    $user = User::factory()->create();
    
    $response = $this->actingAs($user)->post(route('action'), [
        'data' => 'value',
    ]);
    
    $response->assertRedirect(route('success'));
    $this->assertDatabaseHas('table', ['column' => 'value']);
}
```

## Submitting Changes

1. **Push your branch** to your fork
   ```bash
   git push origin feature/your-feature-name
   ```

2. **Create a Pull Request** with:
   - Clear title describing the change
   - Detailed description of what and why
   - Reference to any related issues
   - Screenshots or demos if applicable

3. **Respond to feedback** professionally and promptly

## Pull Request Guidelines

- **One feature per PR** - Keep changes focused
- **Update documentation** - Include README or docs updates
- **Add tests** - Cover new functionality
- **Check CI/CD** - Ensure all checks pass
- **Keep history clean** - Use meaningful commits
- **Base on main** - Create from main branch

### PR Title Format
```
[TYPE] Brief description

Examples:
- [Feature] Add user message notifications
- [Fix] Fix AJAX like button not updating count
- [Docs] Update installation instructions
- [Security] Add XSS protection to comments
```

## Types of Contributions

### Bug Reports
- Describe the bug clearly
- Include steps to reproduce
- Provide expected vs actual behavior
- Include environment details (PHP version, OS, etc.)

### Feature Requests
- Describe the feature and use case
- Explain why it's needed
- Provide examples or mockups if helpful
- Consider potential security implications

### Documentation
- Fix typos and improve clarity
- Add examples for complex features
- Update outdated information
- Translate to other languages

### Code Improvements
- Optimize performance
- Refactor for maintainability
- Add missing error handling
- Improve type hints

## Code Style

### PHP (PSR-12)
```php
// Use meaningful names
$userFollowers = $user->followers()->get();

// Add type hints
public function getUserFollowers(User $user): Collection
{
    return $user->followers;
}

// Comment complex logic
// Fetch only unread messages from active conversations
$unreadMessages = Message::where('is_read', false)
    ->whereHas('conversation', function ($query) {
        $query->where('user_one_id', $user->id)
            ->orWhere('user_two_id', $user->id);
    })
    ->get();
```

### JavaScript
```javascript
// Use const/let instead of var
const userId = user.id;

// Use arrow functions
const doubled = numbers.map(n => n * 2);

// Add JSDoc comments for functions
/**
 * Send a message via AJAX
 * @param {string} conversationId
 * @param {string} body
 * @returns {Promise}
 */
async function sendMessage(conversationId, body) {
    // Implementation
}
```

## Security Considerations

- **Input validation** - Always validate and sanitize user input
- **SQL injection** - Use parameterized queries (Eloquent handles this)
- **XSS prevention** - Use `strip_tags()` or `htmlspecialchars()`
- **CSRF protection** - Always include `@csrf` in forms
- **Authentication** - Verify user authorization before sensitive operations
- **Rate limiting** - Add rate limiting for abuse-prone endpoints

## Performance Guidelines

- Use eager loading (`with()`) to prevent N+1 queries
- Add database indexes for frequently queried columns
- Cache expensive operations
- Optimize database queries
- Minimize JavaScript bundle size
- Use lazy loading for images

## Documentation

Update documentation when:
- Adding new features
- Changing existing behavior
- Fixing bugs (if user-facing)
- Improving performance

Document in:
- README_LINKUP.md - For user-facing features
- Code comments - For complex logic
- Commit messages - For why changes were made

## Review Process

1. **Automated checks**
   - CI/CD pipeline runs tests
   - Code style validation
   - Security checks

2. **Code review**
   - Maintainers review for quality
   - Suggestions or requested changes
   - Approval when ready

3. **Merge**
   - Approved PRs merged to main
   - Delete feature branch
   - Close related issues

## Getting Help

- **Questions?** - Open a discussion or issue
- **Stuck?** - Ask in comments on your PR
- **Feature ideas?** - Start a discussion first
- **Security issue?** - Email privately to maintainers

## License

By contributing, you agree that your contributions will be licensed under the MIT License.

## Recognition

Contributors will be recognized in:
- Project CONTRIBUTORS file
- Release notes for significant contributions
- GitHub insights

Thank you for making LinkUp better!
