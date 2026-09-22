<section id="journal" class="blog-section" x-data="{
    posts: <?php echo e(Js::from($blogPosts ?? [])); ?>,
    selectedPost: null,
    openPost(post) {
        this.selectedPost = post;
        document.body.style.overflow = 'hidden';
    },
    closePost() {
        this.selectedPost = null;
        document.body.style.overflow = '';
    }
}">
    <!-- Section Header -->
    <div class="blog-header-wrapper reveal reveal-fade-up">
        <span class="blog-kicker">EDITORIAL & INSIGHTS</span>
        <h2 class="section-title">THE LEEDEE JOURNAL</h2>
        <p class="section-description">
            Couture stories, fabric masterclasses, and contemporary styling guides from our Karachi design house.
        </p>
    </div>

    <!-- Blog Posts Grid -->
    <div class="blog-grid">
        <template x-for="(post, index) in posts" :key="post.id">
            <article class="blog-card reveal reveal-scale-up"
                     :class="'delay-' + ((index % 3 + 1) * 100)"
                     @click="openPost(post)">
                
                <!-- Media / Image -->
                <div class="blog-card-media" x-data="imageLoader()">
                    <div class="skeleton skeleton-img" x-show="!loaded" style="position: absolute; inset: 0;"></div>
                    <img :src="post.image"
                         :alt="post.title"
                         class="blog-card-img"
                         :class="{ 'img-loaded': loaded }"
                         @load="onLoad()"
                         loading="lazy">
                    <span class="blog-category-badge" x-text="post.category"></span>
                </div>

                <!-- Text Content -->
                <div class="blog-card-content">
                    <div class="blog-meta-row">
                        <span x-text="post.date"></span>
                        <span class="blog-meta-dot"></span>
                        <span x-text="post.read_time"></span>
                    </div>

                    <h3 class="blog-card-title" x-text="post.title"></h3>
                    <p class="blog-card-excerpt" x-text="post.excerpt"></p>

                    <div class="blog-card-cta">
                        <span>Read Article</span>
                        <span>&rarr;</span>
                    </div>
                </div>
            </article>
        </template>
    </div>

    <!-- Article Reader Modal -->
    <template x-if="selectedPost">
        <div class="blog-modal-backdrop" @click.self="closePost()" x-cloak>
            <div class="blog-modal-panel">
                <button type="button" class="blog-modal-close" aria-label="Close story" @click="closePost()">
                    &times;
                </button>

                <!-- Hero Image -->
                <div class="blog-modal-hero">
                    <img :src="selectedPost.image" :alt="selectedPost.title">
                    <span class="blog-category-badge" style="top: 20px; left: 24px;" x-text="selectedPost.category"></span>
                </div>

                <!-- Body Content -->
                <div class="blog-modal-body">
                    <div class="blog-meta-row" style="margin-bottom: 8px;">
                        <span x-text="selectedPost.date"></span>
                        <span class="blog-meta-dot"></span>
                        <span x-text="selectedPost.read_time"></span>
                    </div>

                    <h2 class="blog-modal-title" x-text="selectedPost.title"></h2>

                    <div class="blog-modal-prose" x-text="selectedPost.content"></div>

                    <!-- Article Footer Actions -->
                    <div style="margin-top: 36px; padding-top: 24px; border-top: 1px solid #e5e7eb; display: flex; gap: 14px; flex-wrap: wrap; align-items: center; justify-content: space-between;">
                        <a :href="'https://wa.me/923066978156?text=' + encodeURIComponent('Hi Leedee Fusion, I read your journal article: ' + selectedPost.title + '. I would like to enquire about similar fabrics.')"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="boutique-wa-btn"
                           style="width: auto; margin-top: 0; padding: 10px 20px;">
                            Enquire on WhatsApp
                        </a>

                        <button type="button" @click="closePost()" class="btn-table-action" style="padding: 10px 18px; border-radius: 8px;">
                            Close Story
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>
</section>
<?php /**PATH /Users/mac/Documents/GitHub/Leede-Fusion/resources/views/sections/blog.blade.php ENDPATH**/ ?>