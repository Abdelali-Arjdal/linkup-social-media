// Feed AJAX interactions for likes and comments

document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    // Helper function to escape HTML
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Helper function to get user avatar HTML
    function getUserAvatarHTML(user) {
        if (user.avatar) {
            // Controller already returns full Storage URL
            return `<img src="${escapeHtml(user.avatar)}" alt="${escapeHtml(user.name)}" class="w-8 h-8 rounded-full object-cover flex-shrink-0">`;
        } else {
            const initial = user.name.charAt(0).toUpperCase();
            return `<div class="w-8 h-8 rounded-full bg-gradient-to-br from-accent to-primary flex items-center justify-center text-white font-semibold text-xs flex-shrink-0">${escapeHtml(initial)}</div>`;
        }
    }

    // Handle like/unlike
    document.querySelectorAll('.like-form').forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const form = e.target;
            const button = form.querySelector('.like-button');
            const postId = form.getAttribute('data-post-id');
            const likeCountSpan = form.querySelector('.like-count');
            const likeIconFilled = form.querySelector('.like-icon-filled');
            const likeIconOutline = form.querySelector('.like-icon-outline');
            
            // Disable button during request
            button.disabled = true;
            
            try {
                const formData = new FormData(form);
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    // Update like count
                    likeCountSpan.textContent = data.likeCount;
                    
                    // Toggle icons
                    if (data.isLiked) {
                        likeIconFilled.classList.remove('hidden');
                        likeIconOutline.classList.add('hidden');
                        button.classList.add('text-secondary');
                    } else {
                        likeIconFilled.classList.add('hidden');
                        likeIconOutline.classList.remove('hidden');
                        button.classList.remove('text-secondary');
                    }
                }
            } catch (error) {
                console.error('Error toggling like:', error);
                // Fallback to form submission
                form.submit();
            } finally {
                button.disabled = false;
            }
        });
    });

    // Handle comment submission
    document.querySelectorAll('.comment-form').forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const form = e.target;
            const postId = form.getAttribute('data-post-id');
            const input = form.querySelector('.comment-input');
            const submitBtn = form.querySelector('.comment-submit-btn');
            const commentsContainer = form.closest('[data-post-id]').querySelector('.comments-container');
            const commentCountSpan = form.closest('[data-post-id]').querySelector('.comment-count');
            const viewAllComments = commentsContainer.querySelector('.view-all-comments');
            const commentCountDisplay = commentsContainer.querySelector('.comment-count-display');
            
            const content = input.value.trim();
            
            if (!content) return;
            
            // Disable form during request
            submitBtn.disabled = true;
            input.disabled = true;
            
            try {
                const formData = new FormData(form);
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success && data.comment) {
                    // Clear input
                    input.value = '';
                    
                    // Update comment count
                    if (commentCountSpan) {
                        commentCountSpan.textContent = data.commentCount;
                    }
                    if (commentCountDisplay) {
                        commentCountDisplay.textContent = data.commentCount;
                    }
                    
                    // Create comment HTML (current user can always delete their own comments)
                    const deleteForm = `
                        <form action="/comments/${data.comment.id}" method="POST" class="ml-2 delete-comment-form" data-comment-id="${data.comment.id}">
                            <input type="hidden" name="_token" value="${csrfToken}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="text-gray-400 hover:text-secondary transition text-xs p-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </form>
                    `;
                    
                    const commentHTML = `
                        <div class="comment-item flex items-start space-x-3" data-comment-id="${data.comment.id}">
                            ${getUserAvatarHTML(data.comment.user)}
                            <div class="flex-1 bg-gray-50 rounded-xl px-4 py-2">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <span class="font-semibold text-sm text-gray-900">${escapeHtml(data.comment.user.name)}</span>
                                        <span class="text-sm text-gray-700 ml-2">${escapeHtml(data.comment.content)}</span>
                                    </div>
                                    ${deleteForm}
                                </div>
                                <p class="text-xs text-gray-500 mt-1">${data.comment.created_at}</p>
                            </div>
                        </div>
                    `;
                    
                    // Add comment to container
                    if (commentsContainer) {
                        // Remove "no comments" state if exists
                        const emptyState = commentsContainer.querySelector('.text-center');
                        if (emptyState) {
                            emptyState.remove();
                        }
                        
                        // Insert at the beginning
                        commentsContainer.insertAdjacentHTML('afterbegin', commentHTML);
                        
                        // Update or create "view all" text
                        if (data.commentCount > 5) {
                            if (viewAllComments) {
                                viewAllComments.style.display = 'block';
                            } else {
                                const viewAllHTML = `<p class="text-xs text-gray-500 pl-11 view-all-comments">View all <span class="comment-count-display">${data.commentCount}</span> comments</p>`;
                                commentsContainer.insertAdjacentHTML('beforeend', viewAllHTML);
                            }
                        }
                    }
                    
                    // Attach delete handler to new comment
                    const newComment = commentsContainer.querySelector(`[data-comment-id="${data.comment.id}"]`);
                    if (newComment) {
                        const deleteForm = newComment.querySelector('.delete-comment-form');
                        if (deleteForm) {
                            deleteForm.addEventListener('submit', handleCommentDelete);
                        }
                        
                        // Scroll to new comment with smooth animation
                        newComment.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                        // Add a subtle highlight animation
                        newComment.style.animation = 'fadeIn 0.3s ease-in';
                    }
                }
            } catch (error) {
                console.error('Error adding comment:', error);
                // Fallback to form submission
                form.submit();
            } finally {
                submitBtn.disabled = false;
                input.disabled = false;
            }
        });
    });

    // Handle comment deletion
    const handleCommentDelete = async function(e) {
            e.preventDefault();
            
            const form = e.target;
            const commentId = form.getAttribute('data-comment-id');
            const commentItem = form.closest('.comment-item');
            const postCard = form.closest('[data-post-id]');
            const commentCountSpan = postCard?.querySelector('.comment-count');
            const commentCountDisplay = postCard?.querySelector('.comment-count-display');
            
            if (!confirm('Are you sure you want to delete this comment?')) {
                return;
            }
            
            try {
                const formData = new FormData(form);
                // Laravel method spoofing requires _method field
                formData.append('_method', 'DELETE');
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    // Remove comment from DOM
                    if (commentItem) {
                        commentItem.style.transition = 'opacity 0.3s';
                        commentItem.style.opacity = '0';
                        setTimeout(() => {
                            commentItem.remove();
                            
                            // Update comment count
                            if (commentCountSpan) {
                                commentCountSpan.textContent = data.commentCount;
                            }
                            if (commentCountDisplay) {
                                commentCountDisplay.textContent = data.commentCount;
                            }
                            
                            // Update or remove "view all" text
                            const commentsContainer = postCard?.querySelector('.comments-container');
                            const viewAllComments = commentsContainer?.querySelector('.view-all-comments');
                            if (data.commentCount <= 5 && viewAllComments) {
                                viewAllComments.remove();
                            } else if (viewAllComments) {
                                viewAllComments.querySelector('.comment-count-display').textContent = data.commentCount;
                            }
                            
                            // Show empty state if no comments left
                            if (data.commentCount === 0) {
                                commentsContainer.innerHTML = '';
                            }
                        }, 300);
                    }
                }
            } catch (error) {
                console.error('Error deleting comment:', error);
                // Fallback to form submission
                form.submit();
            }
        };
    
    // Attach delete handlers to existing comments
    document.querySelectorAll('.delete-comment-form').forEach(form => {
        form.addEventListener('submit', handleCommentDelete);
    });
});

// Add fadeIn animation CSS
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-5px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
`;
document.head.appendChild(style);

