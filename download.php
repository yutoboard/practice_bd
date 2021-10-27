<?php

// �f�[�^�x�[�X�̐ڑ����
define( 'DB_HOST', 'localhost');
define( 'DB_USER', 'root');
define( 'DB_PASS', 'root');
define( 'DB_NAME', 'board');

// �ϐ��̏�����
$csv_data = null;
$sql = null;
$pdo = null;
$option = null;
$message_array = array();
$limit = null;
$stmt = null;

session_start();

// �擾����
if( !empty($_GET['limit']) ) {

	if( $_GET['limit'] === "10" ) {
		$limit = 10;
	} elseif( $_GET['limit'] === "30" ) {
		$limit = 30;
	}
}

if( !empty($_SESSION['admin_login']) && $_SESSION['admin_login'] === true ) {

	// �f�[�^�x�[�X�ɐڑ�
	try {

		$option = array(
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::MYSQL_ATTR_MULTI_STATEMENTS => false
		);
		$pdo = new PDO('mysql:charset=UTF8;dbname='.DB_NAME.';host='.DB_HOST , DB_USER, DB_PASS, $option);

		// ���b�Z�[�W�̃f�[�^���擾����
		if( !empty($limit) ) {

			// SQL�쐬
			$stmt = $pdo->prepare("SELECT * FROM message ORDER BY post_date ASC LIMIT :limit");

			// �l���Z�b�g
			$stmt->bindValue( ':limit', $_GET['limit'], PDO::PARAM_INT);

		} else {
			$stmt = $pdo->prepare("SELECT * FROM message ORDER BY post_date ASC");
		}

		// SQL�N�G���̎��s
		$stmt->execute();
		$message_array = $stmt->fetchAll();

		// �f�[�^�x�[�X�̐ڑ������
		$stmt=null;
		$pdo = null;

	} catch(PDOException $e) {

		// �Ǘ��҃y�[�W�փ��_�C���N�g
		header("Location: ./admin.php");
		exit;
	}

	// �o�͂̐ݒ�
	header("Content-Type: text/csv");
	header("Content-Disposition: attachment; filename=���b�Z�[�W�f�[�^.csv");
	header("Content-Transfer-Encoding: binary");

	// CSV�f�[�^���쐬
	if( !empty($message_array) ) {
		
		// 1�s�ڂ̃��x���쐬
		$csv_data .= '"ID","�\����","���b�Z�[�W","���e����"'."\n";
		
		foreach( $message_array as $value ) {
		
			// �f�[�^��1�s����CSV�t�@�C���ɏ�������
			$csv_data .= '"' . $value['id'] . '","' . $value['view_name'] . '","' . $value['message'] . '","' . $value['post_date'] . "\"\n";
		}
	}

	// �t�@�C�����o��	
	echo $csv_data;

} else {

	// ���O�C���y�[�W�փ��_�C���N�g
	header("Location: ./admin.php");
	exit;
}

return;