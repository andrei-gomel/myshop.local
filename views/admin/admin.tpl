<div id="blockNewCategory"><br>
  <h4>Новая категория:</h4>
  <input name="newCategoryName" id="newCategoryName" type="text" value="">
  <br>

  <font color=CadetBlue>Является подкатегорией для</font>
  <select name="generalCatId">
    <option value="0">Главная категория
      {foreach $rsCategories as $item}
         <option value="{$item['id']}">{$item['name']}</option>
      {/foreach}
    </select>
      <br>
      <input type="button" value="Добавить категорию" onclick="newCategory();">
</div>
