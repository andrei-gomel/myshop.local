{* Страница категорий *}
<h2>Товары категории {$rsCategory['name']}</h2><br>

{if !$rsProducts AND !$rsChaldCats}
  <br><br><h3><font color="CadetBlue">В этой категории товаров нет</h3>
{/if}

{foreach $rsProducts as $item name=products}
<div class="border_style_dotted" style="width:270px; float: left; margin: 5px 10px; padding: 10px 10px 10px 10px;">
  <a href="/product/{$item['id']}/">
    <img src="/images/products/{$item['image']}" width="250">
  </a><br>
  <a href="/product/{$item['id']}/" style="color: #228B22">{{$item['name']}}</a>
</div>

  {if $smarty.foreach.products.iteration mod 3 == 0}
    <div style="clear: both;"></div>
  {/if}
{/foreach}

{foreach $rsChaldCats as $item name=childCats}
  <h3><a href="/category/{$item['id']}/" style="color: #228B22">{$item['name']}</a></h3>
{/foreach}
