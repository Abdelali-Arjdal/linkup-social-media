document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    // Handle like button clicks
    document.addEventListener('submit', async function(e) {
        const form = e.target;

        // Handle like form
        if (form.classList.contains('like-form')) {
            e.preventDefault();
            const postId = form.getAttribute('data-post-id');
            const postCard = document.querySelector(`[data-post-id="${postId}"]`);
            const likeButton = form.querySelector('.like-button');
            const likeCount = form.querySelector('.like-count');
            const filledIcon = form.querySelector('.like-icon-filled');
            const outlineIcon = form.querySelector('.like-icon-outline');

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: new FormData(form)
                });

                const data = await response.json();

                if (data.success) {
                    // Update like count
                    likeCount.textContent = data.likeCount;

                    // Toggle heart icon
                    if (data.isLiked) {
                        filledIcon.classList.remove('hidden');
                        outlineIcon.classList.add('hidden');
                    } else {
                        filledIcon.classList.add('hidden');
                        outlineIcon.classList.remove('hidden');
                    }
                }
            } catch (error) {
                console.error('Error liking post:', error);
            }
        }

        // Handle comment form
        if (form.classList.contains('comment-form')) {
            e.preventDefault();
            const postId = form.getAttribute('data-post-id');
            const postCard = document.querySelector(`[data-post-id="${postId}"]`);
            const commentInput = form.querySelector('.comment-input');
            const commentText = commentInput.value.trim();

            if (!commentText) return;

            const submitBtn = form.querySelector('.comment-submit-btn');
            submitBtn.disabled = true;

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: new FormData(form)
                });

                const data = await response.json();

                if (data.success && data.comment) {
                    // Clear input
                    commentInput.value = '';

                    // Update comment count
                    const commentCountEl = postCard.querySelector('.comment-count');
                    commentCountEl.textContent = data.commentCount;

                    // Create and add new comment to container
                    const commentsContainer = postCard.querySelector('.comments-container');
                    const newCommentHTML = `
                        <div class="comment-item flex items-start space-x-3" data-comment-id="${data.comment.id}">
                            <img src="${data.comment.user.avatar || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(data.comment.user.name)}" 
                                 alt="${data.comment.user.name}" 
                                 class="w-8 h-8 rounded-full object-cover flex-shrink-0">
                            <div class="flex-1 bg-gray-50 rounded-xl px-4 py-2">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <span class="font-semibold text-sm text-gray-900">${escapeHtml(data.comment.user.name)}</span>
                                        <span class="text-sm text-gray-700 ml-2">${escapeHtml(data.comment.content)}</span>
                                    </div>
                                    ${data.canDelete ? `
                                        <form action="/comments/${data.comment.id}" method="POST" class="ml-2 delete-comment-form" data-comment-id="${data.comment.id}">
                                            <input type="hidden" name="_token" value="${csrfToken}">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" class="text-gray-400 hover:text-red-500 transition text-xs p-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    ` : ''}
                                </div>
                                <p class="text-xs text-gray-500 mt-1">${data.comment.created_at}</p>
                            </div>
                        </div>
                    `;

                    // Insert at the beginning of comments
                    const firstComment = commentsContainer.querySelector('.comment-item');
                    if (firstComment) {
                        firstComment.insertAdjacentHTML('beforebegin', newCommentHTML);
                    } else {
                        commentsContainer.insertAdjacentHTML('afterbegin', newCommentHTML);
                    }

                    // Attach delete event listener to the new delete form
                    const newDeleteForm = postCard.querySelector(`[data-comment-id="${data.comment.id}"] .delete-comment-form`);
                    if (newDeleteForm) {
                        handleCommentDelete(newDeleteForm, postId, csrfToken);
                    }
                }
            } catch (error) {
                console.error('Error posting comment:', error);
            } finally {
                submitBtn.disabled = false;
            }
        }

        // Handle delete comment
        if (form.classList.contains('delete-comment-form')) {
            e.preventDefault();
            if (!confirm('Are you sure you want to delete this comment?')) return;

            const commentId = form.getAttribute('data-comment-id');
            const postCard = form.closest('[data-post-id]');
            const postId = postCard?.getAttribute('data-post-id');

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: new FormData(form)
                });

                const data = await response.json();

                if (data.success) {
                    // Remove comment from DOM
                    const commentItem = postCard.querySelector(`[data-comment-id="${commentId}"]`);
                    if (commentItem) {
                        commentItem.remove();
                    }

                    // Update comment count
                    const commentCountEl = postCard.querySelector('.comment-count');
                    commentCountEl.textContent = data.commentCount;
                }
            } catch (error) {
                console.error('Error deleting comment:', error);
            }
        }
    }, true);

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function handleCommentDelete(form, postId, csrfToken) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            if (!confirm('Are you sure you want to delete this comment?')) return;

            const commentId = form.getAttribute('data-comment-id');
            const postCard = document.querySelector(`[data-post-id="${postId}"]`);

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: new FormData(form)
                });

                const data = await response.json();

                if (data.success) {
                    const commentItem = postCard.querySelector(`[data-comment-id="${commentId}"]`);
                    if (commentItem) {
                        commentItem.remove();
                    }

                    const commentCountEl = postCard.querySelector('.comment-count');
                    commentCountEl.textContent = data.commentCount;
                }
            } catch (error) {
                console.error('Error deleting comment:', error);
            }
        });
    }
});
