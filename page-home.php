<?php get_header(); ?>

<div class="visible-xs">
  <?php get_template_part('navi' , ''); ?>
</div>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
  <?php the_content(); ?>
<?php endwhile; endif; ?>

<?php get_footer("home"); ?>
