<?php
/**
 * iroha Board Project
 *
 * @author        Kotaro Miura
 * @copyright     2015-2016 iroha Soft, Inc. (http://irohasoft.jp)
 * @link          http://irohaboard.irohasoft.jp
 * @license       http://www.gnu.org/licenses/gpl-3.0.en.html GPL License
 */

App::uses('AppModel', 'Model');

/**
 * Content Model
 *
 * @property Group $Group
 * @property Course $Course
 * @property User $User
 * @property Record $Record
 */
class Content extends AppModel
{

	/**
	 * Validation rules
	 *
	 * @var array
	 */

	public $validate = array(
			'course_id' => array(
					'numeric' => array(
							'rule' => array(
									'numeric'
							)
					// 'message' => 'Your custom message here',
					// 'allowEmpty' => false,
					// 'required' => false,
					// 'last' => false, // Stop validation after this rule
					// 'on' => 'create', // Limit validation to 'create' or
					// 'update' operations
										)
			),
			'user_id' => array(
					'numeric' => array(
							'rule' => array(
									'numeric'
							)
					// 'message' => 'Your custom message here',
					// 'allowEmpty' => false,
					// 'required' => false,
					// 'last' => false, // Stop validation after this rule
					// 'on' => 'create', // Limit validation to 'create' or
					// 'update' operations
										)
			),
			'title' => array(
					'notBlank' => array(
							'rule' => array(
									'notBlank'
							)
					// 'message' => 'Your custom message here',
					// 'allowEmpty' => false,
					// 'required' => false,
					// 'last' => false, // Stop validation after this rule
					// 'on' => 'create', // Limit validation to 'create' or
					// 'update' operations
										)
			),
			'status' => array(
					'notBlank' => array(
							'rule' => array(
									'notBlank'
							)
					// 'message' => 'Your custom message here',
					// 'allowEmpty' => false,
					// 'required' => false,
					// 'last' => false, // Stop validation after this rule
					// 'on' => 'create', // Limit validation to 'create' or
					// 'update' operations
										)
			),
			'timelimit' => array(
					'numeric' => array(
						'rule' => array('range', 0, 101),
						'message' => '1-100の整数で入力して下さい。',
						'allowEmpty' => true,
					// 'required' => false,
					// 'last' => false, // Stop validation after this rule
					// 'on' => 'create', // Limit validation to 'create' or
					// 'update' operations
										)
			),
			'pass_rate' => array(
					'numeric' => array(
						'rule' => array('range', 0, 101),
						'message' => '1-100の整数で入力して下さい。',
						'allowEmpty' => true,
					// 'required' => false,
					// 'last' => false, // Stop validation after this rule
					// 'on' => 'create', // Limit validation to 'create' or
					// 'update' operations
										)
			),
			'question_count' => array(
					'numeric' => array(
						'rule' => array('range', 0, 101),
						'message' => '1-100の整数で入力して下さい。',
						'allowEmpty' => true,
					// 'required' => false,
					// 'last' => false, // Stop validation after this rule
					// 'on' => 'create', // Limit validation to 'create' or
					// 'update' operations
										)
			),
			'kind' => array(
					'notBlank' => array(
							'rule' => array(
									'notBlank'
							)
					// 'message' => 'Your custom message here',
					// 'allowEmpty' => false,
					// 'required' => false,
					// 'last' => false, // Stop validation after this rule
					// 'on' => 'create', // Limit validation to 'create' or
					// 'update' operations
										)
			),
			'sort_no' => array(
					'numeric' => array(
							'rule' => array(
									'numeric'
							)
					// 'message' => 'Your custom message here',
					// 'allowEmpty' => false,
					// 'required' => false,
					// 'last' => false, // Stop validation after this rule
					// 'on' => 'create', // Limit validation to 'create' or
					// 'update' operations
										)
			),
	);

	// The Associations below have been created with all possible keys, those
	// that are not needed can be removed

	/**
	 * belongsTo associations
	 *
	 * @var array
	 */
	public $belongsTo = array(
			'Course' => array(
					'className' => 'Course',
					'foreignKey' => 'course_id',
					'conditions' => '',
					'fields' => '',
					'order' => ''
			),
			'User' => array(
					'className' => 'User',
					'foreignKey' => 'user_id',
					'conditions' => '',
					'fields' => '',
					'order' => ''
			)
	);

	/**
	 * hasMany associations
	 *
	 * @var array
	 */
	public $hasMany = array(
		'ContentsQuestion' => array(
				'className' => 'ContentsQuestion',
				'foreignKey' => 'content_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
		),
		'Record' => array(
				'className' => 'Record',
				'foreignKey' => 'content_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
		),
		'ClearedContent' => array(
				'className' => 'ClearedContent',
				'foreignKey' => 'content_id',
				'dependent' => true
		)
	);

	/**
	 * 学習履歴付きコンテンツ一覧を取得
	 *
	 * @param int $user_id   取得対象のユーザID
	 * @param int $course_id 取得対象のコースID
	 * @param string $role   取得者の権限（admin の場合、非公開のコンテンツも取得）
	 * @var array 学習履歴付きコンテンツ一覧
	 */
	public function getContentRecord($user_id, $course_id, $role = 'user')
	{
		$sql = <<<EOF
 SELECT Content.*, first_date, last_date, record_id, Record.study_sec, Record.study_count,
       (SELECT understanding
          FROM ib_records h1
         WHERE h1.id = Record.record_id
         ORDER BY created
          DESC LIMIT 1) as understanding,
       (SELECT ifnull(is_passed, 0)
          FROM ib_records h2
         WHERE h2.id = Record.record_id
         ORDER BY created
          DESC LIMIT 1) as is_passed
   FROM ib_contents Content
   LEFT OUTER JOIN
       (SELECT h.content_id, h.user_id,
               MAX(DATE_FORMAT(created, '%Y/%m/%d')) as last_date,
               MIN(DATE_FORMAT(created, '%Y/%m/%d')) as first_date,
			   MAX(id) as record_id,
			   SUM(ifnull(study_sec, 0)) as study_sec,
			   COUNT(*) as study_count
		  FROM ib_records h
         WHERE h.user_id    =:user_id
		   AND h.course_id  =:course_id
         GROUP BY h.content_id, h.user_id) Record
     ON Record.content_id  = Content.id
    AND Record.user_id     =:user_id
  WHERE Content.course_id  =:course_id
    AND (status = 1 OR 'admin' = :role)
  ORDER BY Content.sort_no
EOF;

		$params = array(
				'user_id' => $user_id,
				'course_id' => $course_id,
				'role' => $role
		);

		$data = $this->query($sql, $params);

		return $data;
	}

	/**
	 * コンテンツの並べ替え
	 *
	 * @param array $id_list コンテンツのIDリスト（並び順）
	 */
	public function setOrder($id_list)
	{
		for($i=0; $i< count($id_list); $i++)
		{
			$data = array('id' => $id_list[$i], 'sort_no' => ($i+1));
			$this->save($data);
		}
	}

	/**
	 * 新規追加時のコンテンツのソート番号を取得
	 *
	 * @param array $course_id コースID
	 * @return int ソート番号
	 */
	public function getNextSortNo($course_id)
	{
		$options = array(
			'fields' => 'MAX(Content.sort_no) as sort_no',
			'conditions' => array(
				'Content.course_id' => $course_id
			)
		);

		$data = $this->find('first', $options);

		$sort_no = $data[0]['sort_no'] + 1;

		return $sort_no;
	}

  public function getContentInfo($content_id){
		$data = $this->find('all', array(
			'conditions' => array('id' => $content_id),
			'recursive' => -1
		));
    return $data[0];
  }

  public function getContentList($course_id){
		$data = $this->find('all', array(
			'fields' => array(
				'id', 'title', 'sort_no'
			),
			'conditions' => array('course_id' => $course_id),
			'order' => array('sort_no' => 'ASC'),
			'recursive' => -1
		));
    //$this->log($data);
    $content_list = [];
    foreach($data as $row){
      $id = $row['Content']['id'];
      $title = $row['Content']['title'];
      $content_list[$id] = $title;
    }
    return $content_list;
  }

  public function getClearedList($user_id, $course_id){
		App::import('Model', 'ClearedContent');
		$this->ClearedContent = new ClearedContent();
		$data = $this->ClearedContent->find('all', array(
			'fields' => array('content_id', 'user_id'),
			'conditions' => array(
				'course_id' => $course_id,
				'user_id'   => $user_id
			),
			'order' => array('content_id' => 'ASC'),
			'recursive' => -1
		));
		$cleared_list = [];
		foreach($data as $row){
			$content_id = $row['ClearedContent']['content_id'];
			$user_id = $row['ClearedContent']['user_id'];
			$cleared_list[$content_id] = $user_id;
		}
    return $cleared_list;
  }

	public function findFileName($content_id){
		$data = $this->find('first', array(
			'fields' => array('id', 'file_name'),
			'conditions' => array('id' => $content_id),
			'recursive' => -1
		));
		$file_name = $data['Content']['file_name'];
		return $file_name;
	}
}
