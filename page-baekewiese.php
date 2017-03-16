<?php get_header(); ?>

<?php get_template_part('navi' , ''); ?>

<div class="container page-container padding-only-top">
  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
  <!-- <h2 class="seitentitel"><?php the_title(); ?></h2> -->
    <?php the_content(); ?>
  <?php endwhile; endif; ?>
</div><!-- body -->

<?php get_footer(); ?>
