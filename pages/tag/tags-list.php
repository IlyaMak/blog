<?php

include '../../src/autoload.php';

use App\Repository\TagRepository;
use App\Service\DatabaseConnector;

$db = DatabaseConnector::getDatabaseConnection();
$tagRepository = new TagRepository($db);
$tags = $tagRepository->getTags();

$getParentTagName = function (int $tagId) use ($tags, $tagRepository) {
	try {
		echo $tagRepository->getTag(
			$tags[$tagId]['parent_tag_id']
		)['name'];
	} catch (TypeError $e) {
		echo '-';
	}
};

?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Tags List</title>
</head>

<body>
	<h1>Tags List</h1>
	<a href="../tag/create-tag.php">Create tag</a>
	<table>
		<tr>
			<th>Name</th>
			<th>Parent tag</th>
		</tr>
		<?php for ($i = 0; $i <= count($tags) - 1; $i++) {
			if ($tags[$i]['is_visible']) { ?>
				<tr>
					<td><?php echo $tags[$i]['name'] ?></td>
					<td>
						<?php $getParentTagName($i) ?>
					</td>
				</tr>
		<?php }
		} ?>
	</table>
</body>

</html>