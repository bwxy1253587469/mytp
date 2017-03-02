<?php
class CommentController extends BaseController 
{
	public function addAction()
	{
		session_start();
		// 判断有没有登录
		if(!isset($_SESSION['home']))
		{
			echo json_encode(array(
				'ok' => 0,
				'error' => '必须先登录',
			));
			exit;
		}
		if(isset($_POST['goods_id']))
		{
			$model = new CommentModel();
			// 根据表单并根据模型中定义的规则验证表单
				if($model->addComents())
				{
					// 取出会员的头像
					$memberModel = new MemberModel();
					$face = $memberModel->selectByPk($_SESSION['home']['id']);
					// 如果没有设置头像就返回默认头像
					$realFace = !$face['face'] ? '/Public/Home/images/default_face.jpg' : '/Public/Home/'.$face['face'];
					$post = proFrSql($_POST);
					echo json_encode(array(
						'ok' => 1,
						'content' => $post['content'], // 过滤之后的内容
						'addtime' => date('Y-m-d H:i'),
						'star' => $post['star'],
						'email' => $_SESSION['home']['email'],
						'face' => $realFace,
					));
					exit;
				}else
					echo json_encode(array(
						'ok' => 0,
						'error' => $model->getError(),
					));
		}
	}
	public function ajaxGetCommentAction()
	{
		$comment = new CommentModel();
		$data = $comment->getComents();
		//var_dump($data);
		// 处理一下数据
		foreach ($data as $k => $v)
		{
			$data[$k]['face'] = $v['face'] ? '/Public/Home/'.$v['face'] : '/Public/Home/images/default_face.jpg';
			$data[$k]['addtime'] = date('Y-m-d H:i', $v['addtime']);
		}
		echo json_encode($data);
	}
}




















