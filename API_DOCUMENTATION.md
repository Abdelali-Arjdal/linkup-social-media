# LinkUp API Documentation

## Overview

LinkUp provides a RESTful API for all social media operations. All responses return JSON unless otherwise specified.

## Authentication

All protected endpoints require the user to be authenticated. Authentication is handled via Laravel's built-in session management.

### Login
```
POST /login
Content-Type: application/x-www-form-urlencoded

email=user@example.com&password=password
```

Response: Redirect to feed on success, back to login on failure

## Response Format

### Success Response
```json
{
  "success": true,
  "data": {},
  "message": "Operation successful"
}
```

### Error Response
```json
{
  "success": false,
  "error": "Error message",
  "errors": {}
}
```

## Endpoints

### Authentication

#### Register User
```
POST /register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

#### Login
```
POST /login
Content-Type: application/json

{
  "email": "john@example.com",
  "password": "password123",
  "remember": true
}
```

#### Logout
```
POST /logout
```

---

### Posts

#### Create Post
```
POST /posts
Content-Type: application/json
X-CSRF-TOKEN: {csrf_token}

{
  "content": "This is my first post!"
}
```

**Validation:**
- `content`: Required, string, max 2000 characters

**Rate Limit:** 30 posts per hour

**Response:**
```json
{
  "success": true,
  "message": "Post created successfully!"
}
```

#### Delete Post
```
DELETE /posts/{post_id}
X-CSRF-TOKEN: {csrf_token}
```

**Authorization:** User must own the post

**Response:**
```json
{
  "success": true,
  "message": "Post deleted successfully!"
}
```

---

### Comments

#### Add Comment
```
POST /posts/{post_id}/comments
Content-Type: application/json
X-CSRF-TOKEN: {csrf_token}

{
  "content": "Great post!"
}
```

**Validation:**
- `content`: Required, string, max 1000 characters

**AJAX Response:**
```json
{
  "success": true,
  "comment": {
    "id": 123,
    "content": "Great post!",
    "user": {
      "id": 1,
      "name": "John Doe",
      "avatar": "https://..."
    },
    "created_at": "2 minutes ago",
    "created_at_raw": "2026-01-30T12:00:00Z"
  },
  "commentCount": 5,
  "canDelete": true
}
```

#### Delete Comment
```
DELETE /comments/{comment_id}
X-CSRF-TOKEN: {csrf_token}
```

**Authorization:** User must own the comment

**Response:**
```json
{
  "success": true,
  "commentCount": 4,
  "message": "Comment deleted successfully!"
}
```

---

### Likes

#### Toggle Like
```
POST /posts/{post_id}/like
X-CSRF-TOKEN: {csrf_token}
```

**Rate Limit:** 100 per hour

**Response:**
```json
{
  "success": true,
  "isLiked": true,
  "likeCount": 15,
  "message": "Post liked!"
}
```

When unliked:
```json
{
  "success": true,
  "isLiked": false,
  "likeCount": 14,
  "message": "Post unliked."
}
```

---

### Follow System

#### Toggle Follow
```
POST /users/{user_id}/follow
X-CSRF-TOKEN: {csrf_token}
```

**Rate Limit:** 50 per hour

**Authorization:** Cannot follow yourself

**Response:** Redirect with success message

---

### Profiles

#### Get User Profile
```
GET /users/{user_id}
```

**Response:** Returns user profile page with:
- User information (name, bio, avatar)
- Post count
- Follower count
- Following count
- User's posts paginated

#### Get Followers
```
GET /users/{user_id}/followers
```

**Response:** List of users following the user

#### Get Following
```
GET /users/{user_id}/following
```

**Response:** List of users the user is following

#### Edit Profile
```
GET /profile/edit
```

**Response:** Returns profile edit form

#### Update Profile
```
PUT /profile
Content-Type: application/json
X-CSRF-TOKEN: {csrf_token}

{
  "name": "John Doe",
  "email": "john@example.com",
  "bio": "My bio",
  "avatar": "file",
  "password": "newpassword",
  "password_confirmation": "newpassword"
}
```

**Validation:**
- `name`: Required, string, max 255
- `email`: Required, email, unique
- `bio`: Optional, string, max 255
- `avatar`: Optional, image, max 2MB
- `password`: Optional, min 8 characters, confirmed

**Response:**
```json
{
  "success": true,
  "message": "Profile updated successfully!"
}
```

#### Delete Account
```
DELETE /profile
X-CSRF-TOKEN: {csrf_token}
```

**Response:** Redirect to welcome page

---

### Notifications

#### Get Notifications
```
GET /notifications
```

**Response:** List of user's notifications

**Example:**
```json
[
  {
    "id": 1,
    "type": "like",
    "message": "John liked your post",
    "post_id": 123,
    "is_read": false,
    "created_at": "5 minutes ago"
  },
  {
    "id": 2,
    "type": "comment",
    "message": "Jane commented on your post",
    "post_id": 124,
    "comment_id": 45,
    "is_read": false,
    "created_at": "10 minutes ago"
  }
]
```

#### Mark Notification as Read
```
POST /notifications/{notification_id}/read
X-CSRF-TOKEN: {csrf_token}
```

#### Mark All as Read
```
POST /notifications/read-all
X-CSRF-TOKEN: {csrf_token}
```

---

### Messages

#### Get Conversations
```
GET /messages
```

**Response:** Paginated list of conversations with:
- Other user info
- Last message preview
- Unread count
- Updated timestamp

#### Get Conversation
```
GET /messages/{user_id}
```

**Response:** 
- Conversation with all messages
- Other user information
- Message input form

#### Send Message
```
POST /messages/{conversation_id}
Content-Type: application/json
X-CSRF-TOKEN: {csrf_token}

{
  "body": "Hello!"
}
```

**Validation:**
- `body`: Required, string, max 5000

**AJAX Response:**
```json
{
  "success": true,
  "message": {
    "id": 456,
    "body": "Hello!",
    "sender_id": 1,
    "created_at": "now"
  }
}
```

---

### Search

#### Search Users
```
GET /search?q={query}
```

**Response:** List of matching users with profiles

---

### Feed

#### Get Feed
```
GET /feed
```

**Response:** 
- Paginated posts from followed users + own posts
- All posts if not following anyone
- Includes comments and likes data

---

## Error Handling

### HTTP Status Codes

| Code | Meaning |
|------|---------|
| 200 | Success |
| 201 | Created |
| 400 | Bad Request - Validation error |
| 401 | Unauthorized - Not authenticated |
| 403 | Forbidden - Not authorized |
| 404 | Not Found |
| 422 | Unprocessable Entity - Validation failed |
| 429 | Too Many Requests - Rate limit exceeded |
| 500 | Server Error |

### Rate Limiting

Requests that exceed rate limits return:
```json
{
  "success": false,
  "message": "Too many requests. Please try again later."
}
```

HTTP Status: 429

### Validation Errors

```json
{
  "message": "The given data was invalid.",
  "errors": {
    "content": ["The content field is required."],
    "email": ["The email has already been taken."]
  }
}
```

HTTP Status: 422

---

## AJAX Requests

### Setup CSRF Token

Include in every AJAX request:

```javascript
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

fetch(url, {
  method: 'POST',
  headers: {
    'X-CSRF-TOKEN': csrfToken,
    'X-Requested-With': 'XMLHttpRequest',
    'Accept': 'application/json',
    'Content-Type': 'application/json'
  },
  body: JSON.stringify(data)
});
```

---

## Example Usage

### Creating a Post
```bash
curl -X POST http://localhost:8000/posts \
  -H "X-CSRF-TOKEN: your_csrf_token" \
  -H "Content-Type: application/json" \
  -d '{"content": "My first post!"}'
```

### Liking a Post
```bash
curl -X POST http://localhost:8000/posts/1/like \
  -H "X-CSRF-TOKEN: your_csrf_token" \
  -H "X-Requested-With: XMLHttpRequest"
```

### Getting Feed
```bash
curl http://localhost:8000/feed \
  -H "Accept: application/json"
```

---

## Pagination

Many endpoints return paginated results. Query parameters:

- `page`: Page number (default: 1)
- `per_page`: Results per page (varies by endpoint)

**Example:**
```
GET /feed?page=2&per_page=20
```

Response includes pagination meta:
```json
{
  "data": [...],
  "current_page": 2,
  "last_page": 5,
  "per_page": 20,
  "total": 100
}
```

---

## Rate Limits

- **Post Creation**: 30 per hour
- **Like/Unlike**: 100 per hour
- **Follow/Unfollow**: 50 per hour
- **Login Attempts**: 5 per minute
- **General**: Standard Laravel throttle limits

---

## Best Practices

1. **Always include CSRF token** for state-changing requests
2. **Use X-Requested-With header** for AJAX requests
3. **Handle errors gracefully** - Check response codes
4. **Implement retry logic** for failed requests
5. **Cache where appropriate** - Reduce API calls
6. **Sanitize user input** - Validate on client and server
7. **Set proper Content-Type headers** - application/json
8. **Use HTTPS in production** - Never over HTTP

---

## Support

For API questions or issues:
1. Check documentation
2. Review error messages
3. Enable debug mode in `.env`
4. Check Laravel logs
5. Open an issue on GitHub
