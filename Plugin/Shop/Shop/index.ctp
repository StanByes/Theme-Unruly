<?= $this->Html->css('Shop.jquery.bootstrap-touchspin.css') ?>

<div id="content">
  <div class="container">
      <h1 class="title">Boutique</h1>
    <?= $this->element('flash') ?>
    <?= $vouchers->get_vouchers() // Les promotions en cours ?>
    <div class="row">
      <div class="col-sm-3">
          
          <?php if($isConnected) { ?>
          <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Mes crédits</h3>
          </div>
          <div class="panel-body">
              <span class="label credits">
            <?php
            if($isConnected) {
              echo $money;
            } else {
              echo $Lang->get('SHOP__TITLE');
            }
            ?>
              </span>
              
              <?php if($isConnected AND $Permissions->can('CREDIT_ACCOUNT')) { ?>
                    <a href="#" data-toggle="modal" data-target="#addmoney" class="btn btn-default btn-block " style="margin-top:20px;"><?= $Lang->get('SHOP__ADD_MONEY') ?></a>
            <?php } ?>
              
          </div>
        </div>
          <?php } ?>

        <div class="panel panel-default sidebar-menu">

          <div class="panel-heading">
            <h3 class="panel-title"><?= $Lang->get('SHOP__CATEGORIES') ?></h3>
          </div>

          <div class="panel-body">
            <ul class="nav nav-pills nav-stacked category-menu">
              <?php foreach ($search_sections as $v) { ?>
                                <li class="active">
                                    <a class="btn btn-secondary text-left dropdown-toggle" type="button"
                                       data-toggle="dropdown">
                                        <?= before_display($v['Section']['name']) ?>
                                        <span class="caret"></span></a>
                                    <ul class="dropdown-menu">
                                        <?php foreach ($search_categories_section[$v['Section']['id']] as $va) { ?>
                                            <li>
                                                <a
                                                        href="<?= $this->Html->url(array('controller' => 'c/' . $va['Category']['id'], 'plugin' => 'shop')) ?>"><?= before_display($va['Category']['name']) ?></a>
                                            </li>
                                        <?php } ?>
                                    </ul>

                                </li>
                            <?php } ?>
                            <?php $i = 0;
                            foreach ($search_categories_without_section as $v) {
                                $i++; ?>
                                <li class="<?= (isset($category) && $v['Category']['id'] == $category || (!isset($category) && $i == 1)) ? ' active' : ''; ?>">
                                    <a href="<?= $this->Html->url(array('controller' => 'c/' . $v['Category']['id'], 'plugin' => 'shop')) ?>"
                                       class="btn btn-secondary"><?= before_display($v['Category']['name']) ?></a>
                                </li>
                            <?php } ?>
            </ul>
          </div>

        </div>
          
          <?php if($isConnected) { ?>
          <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Mon panier</h3>
              </div>
              <div class="panel-body">
                <?php if($isConnected) { ?>
                  <a href="#" data-toggle="modal" data-target="#cart-modal" class="btn btn-primary btn-block "><?= $Lang->get('SHOP__BUY_CART') ?></a>
                <?php } ?>
              </div> 
            </div>
          <?php } ?>

      </div>
      <div class="col-sm-9">

        <div class="row products">
          <?php
          foreach ($search_items as $item) {
            $item = $item['Item'];

            if(!isset($category) AND $item['category'] == $search_first_category OR isset($category) AND $item['category'] == $category) {

              echo '<div class="col-md-4 col-sm-6">';
                echo '<div class="product">';

                  echo '<div class="image">';
                    echo '<a href="#" class="display-item" data-item-id="'.$item['id'].'">';
                      $url = (isset($item['img_url']) && !empty($item['img_url'])) ? $item['img_url'] : 'product_img_default.png';
                      echo $this->Html->image($url, array('class' => 'img-responsive image1', 'width' => '450', 'height' => '450'));
                    echo '</a>';
                  echo '</div>';

                  echo '<div class="text">';
                    echo '<h3><a href="#" class="display-item" data-item-id="'.$item['id'].'">'.$item['name'].'</a></h3>';
                    echo '<p class="price">'.$item['price'].' '.$Configuration->getMoneyName().'</p>';
                    echo '<p class="buttons">';
                      echo '<a href="#" class="btn btn-default display-item" data-item-id="'.$item['id'].'">'.$Lang->get('SHOP__BUY').'</a>';
                    echo '</p>';
                  echo '</div>';

                echo '</div>';
              echo '</div>';

            }
          }
          ?>

        </div>
    </div>
  </div>
</div>
</div>

<script type="text/javascript">
  var LOADING_MSG = '<?= $Lang->get('GLOBAL__LOADING') ?>';
  var ADDED_TO_CART_MSG = '<?= $Lang->get('SHOP__BUY_ADDED_TO_CART') ?> <i class="fa fa-check"></i>';
  var CART_EMPTY_MSG = '<?= $Lang->get('SHOP__BUY_CART_EMPTY') ?>';
  var ITEM_GET_URL = '<?= $this->Html->url(array('controller' => 'shop/ajax_get', 'plugin' => 'shop')); ?>/';
  var VOUCHER_CHECK_URL = '<?= $this->Html->url(array('action' => 'checkVoucher')) ?>/';
  var BUY_URL = '<?= $this->Html->url(array('action' => 'buy_ajax')) ?>';

  var CART_ITEM_NAME_MSG = '<?= $Lang->get('SHOP__ITEM_NAME') ?>';
  var CART_ITEM_PRICE_MSG = '<?= $Lang->get('SHOP__ITEM_PRICE') ?>';
  var CART_ITEM_QUANTITY_MSG = '<?= $Lang->get('SHOP__ITEM_QUANTITY') ?>';
  var CART_ACTIONS_MSG = '<?= $Lang->get('GLOBAL__ACTIONS') ?>';

  var CSRF_TOKEN = '<?= $csrfToken ?>';
</script>
<?= $this->Html->script('Shop.jquery.cookie') ?>
<?= $this->Html->script('Shop.shop') ?>
<?= $this->Html->script('Shop.jquery.bootstrap-touchspin.js') ?>
<div class="modal fade" id="buy-modal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title"><?= $Lang->get('SHOP__BUY_CONFIRM') ?></h4>
      </div>
      <div class="modal-body">
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="cart-modal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title"><?= $Lang->get('SHOP__BUY_CART') ?></h4>
      </div>
      <div class="modal-body">
      </div>
      <div class="modal-footer">
        <div class="pull-left">
          <input name="cart-voucher" type="text" class="form-control" autocomplete="off" id="cart-voucher" style="width:245px;" placeholder="<?= $Lang->get('SHOP__BUY_VOUCHER_ASK') ?>">
        </div>
        <button class="btn disabled"><?= $Lang->get('SHOP__ITEM_TOTAL') ?> : <span id="cart-total-price">0</span>  <?= $Configuration->getMoneyName() ?></button>
        <button type="button" class="btn btn-primary" id="buy-cart"><?= $Lang->get('SHOP__BUY') ?></button>
      </div>
    </div>
  </div>
</div>
<?= $this->element('payments_modal') ?>
