<main class="page__main page__main--adding-post">
    <div class="page__main-section">
        <div class="container">
            <h1 class="page__title page__title--adding-post">Добавить публикацию</h1>
        </div>
        <div class="adding-post container">
            <div class="adding-post__tabs-wrapper tabs">
                <?php if (isset($content_types)) : ?>
                    <div class="adding-post__tabs filters">
                        <ul class="adding-post__tabs-list filters__list tabs__list">
                            <?php foreach($content_types as $content_type): ?>
                                <li class="adding-post__tabs-item filters__item">
                                    <a class="adding-post__tabs-link filters__button filters__button--<?=$content_type['type_class'];?> tabs__item button <?= ($form_type == $content_type['type_class']) ? 'tabs__item--active filters__button--active' : '' ?>" href="add.php?tab=<?= $content_type['type_class']?>">
                                        <svg class="filters__icon" width="22" height="18">
                                            <use xlink:href="#icon-filter-<?= $content_type['type_class']; ?>"></use>
                                        </svg>
                                        <span><?= $content_type['type_name']; ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <div class="adding-post__tab-content">
                    <?php foreach($content_types as $content_type) {
                        if ($form_type == $content_type['type_class']) {
                            $form = include_template($content_type['type_class'].'-form.php', ['form_values' => $form_values, 'form_errors' => $form_errors, 'field_error_codes' => $field_error_codes, 'form_type' => $form_type]);
                        } else {
                            $form = include_template($content_type['type_class'].'-form.php', ['form_values' => [], 'form_errors' => [], 'field_error_codes' => $field_error_codes, 'form_type' => $form_type]);
                        }
                        print($form);
                    } ?>
                </div>
            </div>
        </div>
    </div>
</main>
