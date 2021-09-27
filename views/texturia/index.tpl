{* Шаблон главной страницы *}

{foreach $rsProducts as $item name=products}
<div class="card border-secondary mb-3" style="max-width: 18rem;">
  <div class="card-body">
    <a href="/product/{$item['id']}/">
      <img src="/images/products/{$item['image']}" width="250">
    </a>
  </div>
  <div class="card-header">
    <a href="/product/{$item['id']}/">{$item['name']}</a>
  </div>
</div>
{if $smarty.foreach.products.iteration mod 3 == 0}
  <div style="clear: both;"></div>
{/if}
{/foreach}

<br><br>
