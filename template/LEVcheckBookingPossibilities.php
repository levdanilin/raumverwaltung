<?php
include("../mapper/LoginCheck.php");
LoginCheck::activitätCheck();
?>

<div>
  <form id="lookupRoom" class="searchInput hidden-print">
    <div id="bookingPossibilities" class="searchInput">
      <div class="search-item">
        <p class="zzz">Etage</p>
        <p id="floor" class="yyy"></p>
      </div>
      <div class="search-item">
        <p class="zzz">Gebäude</p>
        <p id="building" class="yyy"></p>
      </div>
    </div>
    <div id="roomNum" class="search-item">
      <label for="roomNumbers">Raumnummer</label>
      <select id="roomNumbers"></select>
    </div>
    <div class="search-item">
      <label for="startDate">Startdatum</label>
      <input id="startDate" type="date" name="startDate" min="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d', strtotime(date('Y-m-d') . ' + 1 months')); ?>" value="">
    </div>
    <?php
      if ($_SESSION['rights'] == "administrator") {
        echo '<div class="search-item">
              <label for="endDate">Enddatum</label>
              <input id="endDate" type="date" name="endDate" min="' . date('Y-m-d') . '" value=""></div>';
      } else{
        echo '<input id="endDate" type="hidden" name="endDate" value="">';
      }
    ?>
  </form>
  <button id="toggleDetailsButton" onclick="toggleDetails(event);" class="button text-xl hidden-print">Details ausblenden</button>
  <table class="hidden-print">
    <tr id="listOfTimeperiods"></tr>
    <tr>
      <td id="morning"></td>
      <td id="day"></td>
      <td id="evening"></td>
    </tr>
  </table>
  <ul id="bookingResultData" class="print-search-results"></ul>
  <button onclick="window.print();" class="button text-xl hidden-print">Drucken</button>
</div>
