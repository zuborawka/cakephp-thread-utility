<?php

/**
 * Class ThreadUtility
 *
 * 'threaded' タイプで取得したデータを扱うためのユティリティメソッドを提供します。
 *
 * It provides utility methods to handle records found as 'threaded' type.
 */
class ThreadUtility {

	/**
	 * スレッド形式のデータ[= Model::find('threaded')で取得したデータ] における、最大深度を計測して返します。
	 *
	 * Returns the max depth of thread array
	 *
	 * @param array $thread
	 *
	 * @return int
	 */
	public static function maxDepthOfThread(array $thread)
	{
		return 0;
	}

	/**
	 * スレッド形式のデータを、HTMLのテーブルのコードに利用な形式に再構築して返します。
	 * 元のツリー上のデータは、新しく各行にフラットに配置されます。
	 * 各データの 'children' キーの値は破棄され、新たに 'colSpan' と 'rowSpan' の2つの整数値がセットされています。
	 * HTMLコーディングの際に、これらの値を用いることで元の入れ子状態を表現したテーブルの記述が可能になります。
	 *
	 * Returns reconstructed array for table code from threaded array.
	 *
	 * [usage]
	 * // in CommentsController
	 * $conditions = array('Comment.lft >' => $parentLeft, 'Comment.rght <' => $parentRight);
	 * $commentTree = $this->Comment->find('threaded', compact('conditions'));
	 * $tableRows = ThreadUtility::threadToRows($commentTree);
	 * $this->set(compact('tableRows'));
	 *
	 * // in Comments/view/123
	 * <table>
	 *     <tbody>
	 *         <?php
	 *         foreach ($tableRows as $tableRow):
	 *         ?>
	 *         <tr>
	 *         <?php
	 *             foreach ($tableRow as $comment):
	 *                 $colSpan = $comment['colSpan'] === 1 ? '' : ' colspan="' . $comment['colSpan'];
	 *                 $rowSpan = $comment['rowSpan'] === 1 ? '' : ' rowspan="' . $comment['rowSpan'];
	 *                 $name = h($comment['Comment']['name']);
	 *                 $content = h($comment['Comment']['content']);
	 *                 printf('<td%s%s><span class="user">%s</span><div class="contents">%s</div></td>', $colspan, $rowspan, $name, $content);
	 *             endforeach;
	 *         ?>
	 *         </tr>
	 *         <?php
	 *         endforeach;
	 *         ?>
	 *     </tbody>
	 * </table>
	 *
	 * @param array $thread Records found as 'threaded' type
	 *
	 * @return array
	 */
	public static function threadToRows(array $thread)
	{
		return array();
	}
}