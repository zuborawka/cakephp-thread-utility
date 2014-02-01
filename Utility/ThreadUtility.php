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
	 * @param     $thread
	 * @param int $depth
	 * @param int $maxDepth
	 *
	 * @return int
	 */
	protected static function _maxDepthOfThread($thread, $depth = 1, $maxDepth = 1)
	{
		$depth++;
		foreach ($thread as $_thread) {
			if (! empty($_thread['children'])) {
				if ($maxDepth < $depth) {
					$maxDepth = $depth;
				}
				$maxDepth = self::_maxDepthOfThread($_thread['children'], $depth, $maxDepth);
			}
		}

		return $maxDepth;
	}

	/**
	 * @param $thread
	 */
	protected static function _setRowSpan(&$thread)
	{
		foreach ($thread as $i => $_thread) {
			$children = $_thread['children'];
			self::_setRowSpan($children);
			$thread[$i]['children'] = $children;
			$thread[$i]['rowSpan'] = self::_countRowSpan($_thread);
		}
	}

	/**
	 * @param $thread
	 *
	 * @return int
	 */
	protected static function _countRowSpan($thread)
	{
		if (empty($thread['children'])) {
			return 1;
		}

		$num = 0;
		foreach ($thread['children'] as $child) {
			$num += self::_countRowSpan($child);
		}

		return $num;
	}

	/**
	 * @param       $thread
	 * @param       $maxDepth
	 * @param int   $currentDepth
	 * @param array $positioning
	 * @param array $rows
	 *
	 * @return array
	 */
	protected static function _threadToRows($thread, $maxDepth, $currentDepth = 0, &$positioning = array(), $rows = array())
	{
		foreach ($thread as $_thread) {
			$children = $_thread['children'];
			unset($_thread['children']);
			$colSpan = $children ? 1 : $maxDepth - $currentDepth;
			$rowSpan = $_thread['rowSpan'];
			$pos = isset($positioning[$currentDepth]) ? $positioning[$currentDepth] : 0;
			$nextRowPos = $pos + $rowSpan;
			for ($_ = 0; $_ < $colSpan; $_++) {
				$positioning[$currentDepth + $_] = $nextRowPos;
			}
			$_thread['colSpan'] = $colSpan;
			if (! isset($rows[$pos])) {
				$rows[$pos] = array();
			}
			$rows[$pos][$currentDepth] = $_thread;
			$rows = self::_threadToRows($children, $maxDepth, $currentDepth + 1, $positioning, $rows);
		}
		return $rows;
	}


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
		if (empty($thread)) {
			return 0;
		}
		return self::_maxDepthOfThread($thread);
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
	 *                 printf('<td%s%s><span class="user">%s</span><div class="contents">%s</div></td>', $colSpan, $rowSpan, $name, $content);
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
		self::_setRowSpan($thread);
		$maxDepth = self::_maxDepthOfThread($thread);
		return self::_threadToRows($thread, $maxDepth);
	}
}
