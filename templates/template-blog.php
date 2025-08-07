<?php
/**
 * Template Name: Blog
 */
get_header();

// Set up the query for blog posts
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$args = array(
    'post_type' => 'post',
    'post_status' => 'publish',
    'posts_per_page' => 6, // Show 6 posts per page
    'paged' => $paged,
    'orderby' => 'date',
    'order' => 'DESC'
);
$blog_query = new WP_Query($args);
?>

<main class="section">

    <div class="container">
        <nav aria-label="breadcrumb" class="blog-breadcrumbs mt-4">
            <ol class="breadcrumb bg-transparent p-0">
                <li class="breadcrumb-item"><a href="<?php echo home_url(); ?>"><?php echo function_exists('pll__') ? pll__('Головна') : 'Головна'; ?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo function_exists('pll__') ? pll__('Блог') : 'Блог'; ?></li>
            </ol>
        </nav>

        <h1 class="blog-title mb-5"><?php echo function_exists('pll__') ? pll__('Блог') : 'Блог'; ?></h1>

        <div class="row blog-grid">
            <?php if ($blog_query->have_posts()) : ?>
                <?php while ($blog_query->have_posts()) : $blog_query->the_post(); ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <article class="blog-card">
                            <a href="<?php the_permalink(); ?>" class="blog-card__link">
                                <div class="blog-card__image-wrapper">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <?php the_post_thumbnail('medium', array('class' => 'blog-card__image')); ?>
                                    <?php else : ?>
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/placeholder.jpg" alt="<?php the_title_attribute(); ?>" class="blog-card__image">
                                    <?php endif; ?>
                                </div>
                                <div class="blog-card__content">
                                    <h4 class="blog-card__title"><?php the_title(); ?></h4>
                                    <p class="blog-card__excerpt"><?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?></p>
                                </div>
                                <div class="blog-card__button">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M11.34 11.34L18.34 4.34" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M18.34 11.34V4.34H11.34" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                            </a>
                        </article>
                    </div>
                <?php endwhile; ?>
            <?php else : ?>
                <div class="col-12">
                    <p><?php echo function_exists('pll__') ? pll__('Записи не знайдено') : 'Записи не знайдено'; ?></p>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($blog_query->max_num_pages > 1) : ?>
            <nav aria-label="<?php echo function_exists('pll__') ? pll__('Навігація по сторінках') : 'Навігація по сторінках'; ?>" class="blog-pagination">
                <?php
                // Use the custom bootstrap_pagination function with our custom query
                bootstrap_pagination($blog_query);
                ?>
            </nav>
        <?php endif; ?>
    </div>

    <section class="cta">
        <div class="container">
            <div class="cta__content">
                <div class="cta__text">
                    <p class="cta__question"><?php echo function_exists('pll__') ? pll__('Маєш сумніви?') : 'Маєш сумніви?'; ?></p>
                    <p class="cta__subtext"><?php echo function_exists('pll__') ? pll__('Не знаєш що тобі потрібно?') : 'Не знаєш що тобі потрібно?'; ?></p>
                    <h3 class="cta__title"><?php echo function_exists('pll__') ? pll__('Розкажи нам свій запит і ми тобі допоможемо!') : 'Розкажи нам свій запит і ми тобі допоможемо!'; ?></h3>
                </div>
                <div class="cta__action">
                    <a href="#form" class="cta__button"><?php echo function_exists('pll__') ? pll__('Заповнити форму') : 'Заповнити форму'; ?></a>
                </div>
            </div>
        </div>
    </section>
</main>

<?php 
wp_reset_postdata(); // Reset the post data
get_footer(); 
?>



