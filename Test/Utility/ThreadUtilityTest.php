<?php
/**
 * Created by PhpStorm.
 * User: zuborawka
 * Date: 14/01/30
 * Time: 02:21
 */

App::uses('ThreadUtility', 'CakephpThreadUtility.Utility');

/**
 * Class ThreadUtilityTest
 */
class ThreadUtilityTest extends CakeTestCase {

	public function testMaxDepthOfThread()
	{
		$threaded = array(
		);
		$result = ThreadUtility::maxDepthOfThread($threaded);
		$expected = 0;
		$this->assertEquals($expected, $result);

		$threaded = array(
			array(
				'children' => array(),
			),
		);
		$result = ThreadUtility::maxDepthOfThread($threaded);
		$expected = 1;
		$this->assertEquals($expected, $result);

		$threaded = array(
			array(
				'children' => array(
					array(
						'children' => array(
						),
					),
				),
			),
		);
		$result = ThreadUtility::maxDepthOfThread($threaded);
		$expected = 2;
		$this->assertEquals($expected, $result);

		$threaded = array(
			array(
				'children' => array(
					array(
						'children' => array(
						),
					),
					array(
						'children' => array(
							array(
								'children' => array(
									array(
										'children' => array(
										),
									),
								),
							),
						),
					),
					array(
						'children' => array(
							array(
								'children' => array(
								),
							),
						),
					),
				),
			),
		);
		$result = ThreadUtility::maxDepthOfThread($threaded);
		$expected = 4;
		$this->assertEquals($expected, $result);

		$threaded = array(
			array(
				'children' => array(
					array(
						'children' => array(
						),
					),
					array(
						'children' => array(
							array(
								'children' => array(
									array(
										'children' => array(
										),
									),
								),
							),
						),
					),
					array(
						'children' => array(
							array(
								'children' => array(
								),
							),
						),
					),
					array(
						'children' => array(
							array(
								'children' => array(
								),
							),
						),
					),
					array(
						'children' => array(
							array(
								'children' => array(
								),
							),
						),
					),
					array(
						'children' => array(
							array(
								'children' => array(
								),
							),
						),
					),
					array(
						'children' => array(
							array(
								'children' => array(
								),
							),
						),
					),
				),
			),
		);
		$result = ThreadUtility::maxDepthOfThread($threaded);
		$expected = 4;
		$this->assertEquals($expected, $result);
	}

	public function testThreadToRows()
	{
		/**
		 * empty
		 */
		$threaded = array();
		$expected = array();
		$result = ThreadUtility::threadToRows($threaded);
		$this->assertEquals($expected, $result);

		/**
		 * ---
		 * [1]
		 * ---
		 */
		$threaded = array(
			array(
				'Node' => array(
					'id' => '1',
					'name' => 'First node',
				),
				'children' => array(),
			),
		);
		$expected = array(
			array(
				array(
					'Node' => array(
						'id' => '1',
						'name' => 'First node',
					),
					'rowspan' => 1,
					'colspan' => 1,
				),
			),
		);
		$result = ThreadUtility::threadToRows($threaded);
		$this->assertEquals($expected, $result);

		/**
		 * ------
		 * [1][2]
		 * ------
		 */
		$threaded = array(
			array(
				'Node' => array(
					'id' => '1',
					'name' => 'First node',
				),
				'children' => array(
					array(
						'Node' => array(
							'id' => '2',
							'name' => 'Second node',
						),
						'children' => array(),
					),
				),
			),
		);
		$expected = array(
			array(
				array(
					'Node' => array(
						'id' => '1',
						'name' => 'First node',
					),
					'rowspan' => 1,
					'colspan' => 1,
				),
				array(
					'Node' => array(
						'id' => '2',
						'name' => 'Second node',
					),
					'rowspan' => 1,
					'colspan' => 1,
				),
			),
		);
		$result = ThreadUtility::threadToRows($threaded);
		$this->assertEquals($expected, $result);

		/**
		 * ---
		 * [1]
		 * [2]
		 * ---
		 */
		$threaded = array(
			array(
				'Node' => array(
					'id' => '1',
					'name' => 'First node',
				),
				'children' => array(),
			),
			array(
				'Node' => array(
					'id' => '2',
					'name' => 'Second node',
				),
				'children' => array(),
			),
		);
		$expected = array(
			array(
				array(
					'Node' => array(
						'id' => '1',
						'name' => 'First node',
					),
					'rowspan' => 1,
					'colspan' => 1,
				),
			),
			array(
				array(
					'Node' => array(
						'id' => '2',
						'name' => 'Second node',
					),
					'rowspan' => 1,
					'colspan' => 1,
				),
			),
		);
		$result = ThreadUtility::threadToRows($threaded);
		$this->assertEquals($expected, $result);

		/**
		 * ------
		 * [1][3]
		 * [2...]
		 * ------
		 */
		$threaded = array(
			array(
				'Node' => array(
					'id' => '1',
					'name' => 'First node',
				),
				'children' => array(
					array(
						'Node' => array(
							'id' => '3',
							'name' => 'Third node',
						),
						'children' => array(),
					),
				),
			),
			array(
				'Node' => array(
					'id' => '2',
					'name' => 'Second node',
				),
				'children' => array(),
			),
		);
		$expected = array(
			array(
				array(
					'Node' => array(
						'id' => '1',
						'name' => 'First node',
					),
					'rowspan' => 1,
					'colspan' => 1,
				),
				array(
					'Node' => array(
						'id' => '3',
						'name' => 'Third node',
					),
					'rowspan' => 1,
					'colspan' => 1,
				),
			),
			array(
				array(
					'Node' => array(
						'id' => '2',
						'name' => 'Second node',
					),
					'rowspan' => 1,
					'colspan' => 2,
				),
			),
		);
		$result = ThreadUtility::threadToRows($threaded);
		$this->assertEquals($expected, $result);

		/**
		 * ------
		 * [1...]
		 * [2][3]
		 * ------
		 */
		$threaded = array(
			array(
				'Node' => array(
					'id' => '1',
					'name' => 'First node',
				),
				'children' => array(),
			),
			array(
				'Node' => array(
					'id' => '2',
					'name' => 'Second node',
				),
				'children' => array(
					array(
						'Node' => array(
							'id' => '3',
							'name' => 'Third node',
						),
						'children' => array(),
					),
				),
			),
		);
		$expected = array(
			array(
				array(
					'Node' => array(
						'id' => '1',
						'name' => 'First node',
					),
					'rowspan' => 1,
					'colspan' => 2,
				),
			),
			array(
				array(
					'Node' => array(
						'id' => '2',
						'name' => 'Second node',
					),
					'rowspan' => 1,
					'colspan' => 1,
				),
				array(
					'Node' => array(
						'id' => '3',
						'name' => 'Third node',
					),
					'rowspan' => 1,
					'colspan' => 1,
				),
			),
		);
		$result = ThreadUtility::threadToRows($threaded);
		$this->assertEquals($expected, $result);

		/**
		 * ------
		 * [1][2]
		 * [.][3]
		 * [.][4]
		 * ------
		 */
		$threaded = array(
			array(
				'Node' => array(
					'id' => '1',
					'name' => 'First node',
				),
				'children' => array(
					array(
						'Node' => array(
							'id' => '2',
							'name' => 'Second node',
						),
						'children' => array(),
					),
					array(
						'Node' => array(
							'id' => '3',
							'name' => 'Third node',
						),
						'children' => array(),
					),
					array(
						'Node' => array(
							'id' => '4',
							'name' => 'Fourth node',
						),
						'children' => array(),
					),
				),
			),
		);
		$expected = array(
			array(
				array(
					'Node' => array(
						'id' => '1',
						'name' => 'First node',
					),
					'rowspan' => 3,
					'colspan' => 1,
				),
				array(
					'Node' => array(
						'id' => '2',
						'name' => 'Second node',
					),
					'rowspan' => 1,
					'colspan' => 1,
				),
			),
			array(
				1 => array(
					'Node' => array(
						'id' => '3',
						'name' => 'Third node',
					),
					'rowspan' => 1,
					'colspan' => 1,
				),
			),
			array(
				1 => array(
					'Node' => array(
						'id' => '4',
						'name' => 'Fourth node',
					),
					'rowspan' => 1,
					'colspan' => 1,
				),
			),
		);
		$result = ThreadUtility::threadToRows($threaded);
		$this->assertEquals($expected, $result);

		/**
		 * ---------
		 * [1][2...]
		 * [.][3...]
		 * [.][4][5]
		 * ---------
		 */
		$threaded = array(
			array(
				'Node' => array(
					'id' => '1',
					'name' => 'First node',
				),
				'children' => array(
					array(
						'Node' => array(
							'id' => '2',
							'name' => 'Second node',
						),
						'children' => array(),
					),
					array(
						'Node' => array(
							'id' => '3',
							'name' => 'Third node',
						),
						'children' => array(),
					),
					array(
						'Node' => array(
							'id' => '4',
							'name' => 'Fourth node',
						),
						'children' => array(
							array(
								'Node' => array(
									'id' => '5',
									'name' => 'Fifth node',
								),
								'children' => array(),
							),
						),
					),
				),
			),
		);
		$expected = array(
			array(
				array(
					'Node' => array(
						'id' => '1',
						'name' => 'First node',
					),
					'rowspan' => 3,
					'colspan' => 1,
				),
				array(
					'Node' => array(
						'id' => '2',
						'name' => 'Second node',
					),
					'rowspan' => 1,
					'colspan' => 2,
				),
			),
			array(
				1 => array(
					'Node' => array(
						'id' => '3',
						'name' => 'Third node',
					),
					'rowspan' => 1,
					'colspan' => 2,
				),
			),
			array(
				1 => array(
					'Node' => array(
						'id' => '4',
						'name' => 'Fourth node',
					),
					'rowspan' => 1,
					'colspan' => 1,
				),
				2 => array(
					'Node' => array(
						'id' => '5',
						'name' => 'Fifth node',
					),
					'rowspan' => 1,
					'colspan' => 1,
				),
			),
		);
		$result = ThreadUtility::threadToRows($threaded);
		$this->assertEquals($expected, $result);
	}

}
