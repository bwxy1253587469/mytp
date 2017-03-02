<?php
class CommentModel extends Model 
{
	public function getComents(){
		// 定义每页显示的条数
		$perpage = 5;
		$p = proFrSql($_GET['p']);
		$offset = ($p-1)*$perpage;  // 从第几条记录开始取
		$goodsId = proFrSql($_GET['goods_id']);
		$sql = "select a.*,b.email,b.face,COUNT(c.id) reply_count from {$this->table} a left join php34_member b ON a.member_id=b.id LEFT JOIN php34_reply c ON a.id=c.comment_id where a.goods_id=$goodsId  group by a.id order by a.id desc limit $offset,$perpage";
		//$data = $comment->field('a.*,b.email,b.face,COUNT(c.id) reply_count')->alias('a')->join('LEFT JOIN php34_member b ON a.member_id=b.id LEFT JOIN php34_reply c ON a.id=c.comment_id')->where(array('a.goods_id'=>array('eq', $goodsId)))->limit("$offset,$perpage")->group('a.id')->order('a.id DESC')->select();
		return $this->Mquery($sql);
	}

	public function addComents(){
		$post = proFrSql($_POST);
		$post['addtime'] = time();
		$post['member_id'] = $_SESSION['home']['id'];
		return $this->insert($post);
	}

}
















