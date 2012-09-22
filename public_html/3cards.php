<html>
<head>
	<!--<meta content="text/html;charset=utf-8" charset="utf-8">-->
	<meta charset="utf-8">
</head>
<body style="width: 300px; height:500px; margin:auto;;">
<?php
$a = array();
$b = array();
$c = array();

if (isset($_POST['a'])) $a = $_POST['a'];
if (isset($_POST['b'])) $b = $_POST['b'];
if (isset($_POST['c'])) $c = $_POST['c'];

if (count($a) < 3) {
$a[] = rand(1,9);
$b[] = rand(1,9);
$c[] = rand(1,9);
}

$winner = null;
$aMark = $bMark = $cMark = 0;
for ($i=0;$i<count($a);$i++) {
	$aMark += $a[$i];
	$bMark += $b[$i];
}

$aMark = $aMark % 10;
$bMark = $bMark % 10;
$cMark = $cMark % 10;

if (count($a) >= 3) {
	$winner = $aMark > $bMark ? 'A'
		: (($bMark > $aMark) ? 'B' : '');
}
?>

<h1>Điểm số</h1>
<form method="POST">
<?php for ($i=0;$i<count($a);$i++): ?>
	<input type="hidden" name="a[]" value="<?= $a[$i]; ?>">
	<input type="hidden" name="b[]" value="<?= $b[$i]; ?>">
<?php endfor; ?>

<table border=1>
<tr>
	<th>A</th><th>B</th>
</tr>
<?php for ($i=0;$i<count($a);$i++): ?>
<tr>
	<td><?= $a[$i]; ?></td>
	<td><?= $b[$i]; ?></td>
</tr>
<?php endfor; ?>
<tr><td colspan="2"><h3>Điểm số</h3></td></tr>
<tr><td><?= $aMark; ?></td><td><?= $bMark; ?></td></tr>
</table>
<?php if (is_null($winner)): ?>
	<button type="submit">Lá tiếp theo</button>
<?php else: ?>
	<h2 style="color: red;"><?= $winner ?> thắng.</h2>
<?php endif; ?>

</form>
</body>
</html>