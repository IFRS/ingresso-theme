<?php get_header(); ?>

<section class="container">
    <div class="row">
        <?php echo get_template_part('partials/carousel'); ?>
    </div>
</section>

<section class="container">
    <div class="row">
        <div class="col-12">
            <h2 class="home-title">Estude no <span class="home-title__destaque">#mundo<strong>IFRS</strong></span></h2>
            <?php if (get_option( 'page_on_front' )) : ?>
                <div class="home-intro">
                    <?php
                        the_post();
                        the_content();
                    ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="container">
    <div class="row">
        <div class="col-12">
            <?php
                $formas = get_terms(array(
                    'taxonomy' => 'forma',
                    'hide_empty' => false,
                ));
            ?>
            <ul class="nav nav-pills" role="tablist">
                <?php foreach ($formas as $forma) : ?>
                    <li class="nav-item">
                        <button class="btn" type="button" data-toggle="pill" data-target="#tab-forma-<?php echo $forma->term_id; ?>" role="tab" aria-controls="collapse-forma-<?php echo $forma->term_id; ?>" aria-selected="false"><?php echo $forma->name; ?></button>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</section>

<div class="content">
    <section class="container">
        <div class="row">
            <div class="col-12">
                <div class="tab-content">
                    <?php foreach ($formas as $forma) : ?>
                        <?php
                            $args = array(
                                'post_type' => 'oportunidade',
                                'nopaging' => true,
                                'posts_per_page' => -1,
                                'tax_query' => array(
                                    array(
                                        'taxonomy' => 'forma',
                                        'terms' => $forma->term_id,
                                    ),
                                ),
                            );

                            $oportunidades = new WP_Query($args);
                        ?>
                        <div class="tab-pane fade" id="tab-forma-<?php echo $forma->term_id; ?>">
                            <?php if ($oportunidades->have_posts()) : ?>
                                <div class="oportunidades">
                                    <?php while ($oportunidades->have_posts()) : $oportunidades->the_post(); ?>
                                        <?php $forma_ingresso = get_the_terms(get_the_ID(), 'forma'); ?>
                                        <div class="oportunidade oportunidade--<?php echo $forma_ingresso[0]->slug; ?>">
                                            <div class="oportunidade__header">
                                                <p class="oportunidade__forma"><?php echo $forma_ingresso[0]->name; ?></p>
                                                <button class="btn oportunidade__btn-toggle">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-linecap="square" stroke-linejoin="arcs" stroke-width="3" viewBox="0 0 24 24">
                                                        <path d="M12 5v14M5 12h14"/>
                                                    </svg>
                                                </button>
                                            </div>
                                            <h3 class="oportunidade__title"><?php the_title(); ?></h3>
                                            <?php
                                                $isencao_inicio = get_post_meta( get_the_ID(), '_oportunidade_isencao_inicio', true );
                                                $isencao_termino = get_post_meta( get_the_ID(), '_oportunidade_isencao_termino', true );

                                                $isencao_inicio = ($isencao_inicio ? gmdate('d/m/y', $isencao_inicio) : false);
                                                $isencao_termino = ($isencao_termino ? gmdate('d/m/y', $isencao_termino) : false);

                                                $inscricao_inicio = gmdate('d/m/y', get_post_meta( get_the_ID(), '_oportunidade_inscricao_inicio', true ));
                                                $inscricao_termino = gmdate('d/m/y', get_post_meta( get_the_ID(), '_oportunidade_inscricao_termino', true ));
                                            ?>
                                            <?php if ($isencao_inicio && $isencao_termino) : ?>
                                                <p class="oportunidade__meta">Isen&ccedil;&atilde;o da Taxa de Inscri&ccedil;&atilde;o de <strong><?php echo $isencao_inicio; ?></strong> at&eacute; <strong><?php echo $isencao_termino; ?></strong></p>
                                            <?php else : ?>
                                                <p class="oportunidade__meta">Inscri&ccedil;&atilde;o <strong>gratuita</strong></p>
                                            <?php endif; ?>
                                            <p class="oportunidade__meta">Inscri&ccedil;&otilde;es de <strong><?php echo $inscricao_inicio; ?></strong> at&eacute; <strong><?php echo $inscricao_termino; ?></strong></p>

                                            <hr class="oportunidade__separador">

                                            <div class="oportunidade__requisitos">
                                                <h4 class="oportunidade__subtitle">Requisitos m&iacute;nimos para o ingresso</h4>
                                                <?php echo wpautop(get_post_meta( get_the_ID(), '_oportunidade_requisitos', true )); ?>
                                            </div>

                                            <hr class="oportunidade__separador">

                                            <div class="oportunidade__unidades">
                                                <h4 class="oportunidade__subtitle">Campi de Oferta</h4>
                                                <?php echo get_the_term_list(get_the_ID(), 'unidade', '<ul class=\"oportunidade__campi-list\"><li class=\"oportunidade__campus\">', '</li><li class=\"oportunidade__campus\">', '</li></ul>'); ?>
                                            </div>

                                            <a href="<?php echo esc_url(get_post_meta( get_the_ID(), '_oportunidade_url', true )); ?>" class="oportunidade__info-link">Mais informa&ccedil;&otilde;es</a>
                                        </div>
                                    <?php endwhile; ?>
                                </div>
                            <?php else : ?>
                                <div class="alert alert-info" role="alert">
                                    Não existem oportunidades para essa forma de ingresso no momento. Fique atento para novas publicações.
                                </div>
                            <?php endif; ?>
                            <?php wp_reset_postdata(); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
</div>

<?php get_footer(); ?>
