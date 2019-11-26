<?php

/* @var $this yii\web\View
 * @var $treeApples Apple[]
 * @var $groundApples Apple[]
 */

$this->title = 'Яблоки';

use common\models\Apple; ?>
<div class="site-index">

    <div class="jumbotron">
        <?= \yii\helpers\Html::button('Сгенерировать яблоки', ['class' => 'btn btn-md generate-apples']); ?>
    </div>

    <div class="frame">
        <main>
            <div class="tree">
                <div class="stem">
                    <div class="branch"></div>
                    <div class="root"></div>
                </div>
                <div class="leaves">
                    <div class="apples">
                        <?php foreach ($treeApples as $apple): ?>
                            <div class="apple <?= $apple->color ?>"   id="<?= $apple->id ?>" style="z-index: 7; top: <?= $apple->top ?>px; left:<?= $apple->left ?>px"></div>
                        <?php endforeach; ?>
                    </div>

                </div>
            </div>
            <div class="ground">
                <div class="apples">
                <?php foreach ($groundApples as $apple): ?>
                    <div class="apple <?= $apple->color ?> <?= $apple->status == Apple::STATUS_ROTTEN ? 'rotten' : '' ?>"  title="<?= $apple->size * 100 ?> %" data-toggle="modal" data-target="#eat-modal" data-id="<?= $apple->id ?>" data-percent="<?= $apple->size * 100 ?> %" style="z-index: 7; left:<?= $apple->left + $apple->id ?>px">
                        <p><?= $apple->size * 100 ?></p>
                    </div>
                <?php endforeach; ?>
                </div>
                <div class="grass"></div>
                <div class="grass small"></div>
            </div>
        </main>
    </div>


    <div class="modal fade bd-example-modal-sm" id="eat-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <form id="eat-form">
                        <input type="hidden" id="eat-apple" name="id">
                        <div class="form-group">
                            <label for="message-text" class="col-form-label">Сколько откусить? (осталось <t id="cur-percent"></t>)</label>
                            <input type="text" class="form-control" name="percent">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary eat-button">Откусить</button>
                </div>
            </div>
        </div>
    </div>

</div>
