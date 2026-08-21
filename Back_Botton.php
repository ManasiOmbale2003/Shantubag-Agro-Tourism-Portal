<?php
// back_button.php
// If you want a different dashboard file name, change this constant here:
if (!defined('DASHBOARD_URL')) define('DASHBOARD_URL', 'dashboard.php');
?>
<style>
/* simple, neutral styling — adjust as you like */
.btn-back {
  display: inline-block;
  padding: 8px 12px;
  margin: 8px 0;
  border-radius: 6px;
  text-decoration: none;
  border: 1px solid #ccc;
  font-family: Arial, sans-serif;
  font-size: 14px;
  cursor: pointer;
  background: #fff;
}
.btn-back:hover { box-shadow: 0 2px 6px rgba(0,0,0,0.08); }
</style>

<a class="btn-back" href="<?php echo htmlspecialchars(DASHBOARD_URL, ENT_QUOTES); ?>">
  &#8592; Back to Dashboard
</a>
