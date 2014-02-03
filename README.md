cakephp-thread-utility
======================

Utility class to handle threaded data generated by CakePHP Model::find('threaded')

### Usage

#### in CategoriesController

// Category model acts as Tree

```php
$threaded = $this->Category->find('threaded');
$tableRows = ThreadUtility::threadToRows($threaded);
```

#### in view

```php
<table>
  <tbody>
  <?php
  foreach ($tableRows as $tableRow):
    ?>
    <tr>
    <?php
    foreach ($tableRow as $tableCell):
      $colspan = $tableCell['colspan'] > 1 ? ' colspan="' . $tableCell['colspan'] . '"' : '';
      $rowspan = $tableCell['rowspan'] > 1 ? ' rowspan="' . $tableCell['rowspan'] . '"' : '';
      $name = h($tableCell['Category']['name']);
      printf('<td%s%s>%s</td>', $colspan, $rowspan, $name);
    endforeach;
    ?>
    </tr>
    <?php
  endforeach;
  ?>
  </tbody>
</table>
```

#### an example of output

```html
<table>
  <tbody>
    <tr>
      <td rowspan="2">Music</td>
      <td>Pops</td>
    </tr>
    <tr>
      <td>Jazz</td>
    </tr>
    <tr>
      <td colspan="2">Architecture</td>
    </tr>
    <tr>
      <td>Food</td>
      <td>Pasta</td>
    </tr>
  </tbody>
</table>
```

<table>
  <tbody>
    <tr>
      <td rowspan="2">Music</td>
      <td>Pops</td>
    </tr>
    <tr>
      <td>Jazz</td>
    </tr>
    <tr>
      <td colspan="2">Architecture</td>
    </tr>
    <tr>
      <td>Food</td>
      <td>Pasta</td>
    </tr>
  </tbody>
</table>
