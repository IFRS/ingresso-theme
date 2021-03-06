<div class="card curso-item">
    <div class="card-header">
        <?php foreach (get_the_terms(get_the_ID(), 'unidade') as $unidade) : ?>
            <span class="curso-item__unidade"><?php echo $unidade->name; ?></span>
        <?php endforeach; ?>
    </div>
    <div class="card-body">
        <h2 class="card-title curso-item__title"><a href="<?php the_permalink(); ?>" class="curso-item__link"><?php the_title(); ?></a></h2>
        <p class="card-text">
            <?php $niveis = wp_get_post_terms(get_the_ID(), 'nivel', array('orderby' => 'term_id')); ?>
            <?php foreach ($niveis as $nivel) : ?>
                <span class="curso-item__nivel">
                    <?php echo $nivel->name; ?>
                    <?php echo ($nivel !== end($niveis)) ? '<strong> / </strong>' : ''; ?>
                </span>
            <?php endforeach; ?>
            <?php foreach (get_the_terms(get_the_ID(), 'modalidade') as $modalidade) : ?>
                <span class="curso-item__modalidade"><?php echo $modalidade->name; ?></span>
            <?php endforeach; ?>
        </p>
    </div>
    <div class="card-footer">
        <?php $turnos = wp_get_post_terms(get_the_ID(), 'turno', array('orderby' => 'term_order')); ?>
        <?php foreach ($turnos as $turno) : ?>
            <span class="curso-item__turnos"><?php echo $turno->name; echo ($turno !== end($turnos)) ? ', ' : ''; ?></span>
        <?php endforeach; ?>
    </div>
</div>
