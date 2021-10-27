// �y�[�W�̓ǂݍ��݂�����������R�[���o�b�N�֐����Ă΂��
// ���R�[���o�b�N: ��2�����̖����֐�(=�֐������ȗ����ꂽ�֐�)
window.addEventListener('load', () => {
  const canvas = document.querySelector('#draw-area');
  // context���g����canvas�ɊG�������Ă���
  const context = canvas.getContext('2d');

  // ���O�̃}�E�X��canvas���x���W��y���W���L�^����
  const lastPosition = { x: null, y: null };

  // �}�E�X���h���b�O����Ă��邩(�N���b�N���ꂽ�܂܂�)���f���邽�߂̃t���O
  let isDrag = false;

  // �G������
  function draw(x, y) {
    // �}�E�X���h���b�O����Ă��Ȃ������珈���𒆒f����B
    // �h���b�O���Ȃ��炵���G���������Ƃ��o���Ȃ��B
    if(!isDrag) {
      return;
    }

    // �ucontext.beginPath()�v�Ɓucontext.closePath()�v��s�xdraw�֐����Ŏ��s��������A
    // ���̕`���n��(dragStart�֐�)�Ɛ��̕`���I���(dragEnd)��1�񂸂ǂ񂾂ق�������Y��ɐ��揑����

    // ���̏�Ԃ��`����
    // MDN CanvasRenderingContext2D: https://developer.mozilla.org/en-US/docs/Web/API/CanvasRenderingContext2D/lineJoin
    context.lineCap = 'round'; // �ۂ݂�тт����ɂ���
    context.lineJoin = 'round'; // �ۂ݂�тт����ɂ���
    context.lineWidth = 5; // ���̑���
    context.strokeStyle = 'black'; // ���̐F

    // �����n�߂� lastPosition.x, lastPosition.y �̒l��null�ƂȂ��Ă��邽�߁A
    // �N���b�N�����Ƃ�����J�n�_�Ƃ��Ă���B
    // ���̊֐�(draw�֐���)�̍Ō��2�s�� lastPosition.x��lastPosition.y��
    // ���݂�x, y���W���L�^���邱�ƂŁA���Ƀ}�E�X�𓮂��������ɁA
    // �O��̈ʒu���猻�݂̃}�E�X�̈ʒu�܂Ő��������悤�ɂȂ�B
    if (lastPosition.x === null || lastPosition.y === null) {
      // �h���b�O�J�n���̐��̊J�n�ʒu
      context.moveTo(x, y);
    } else {
      // �h���b�O���̐��̊J�n�ʒu
      context.moveTo(lastPosition.x, lastPosition.y);
    }
    // context.moveTo�Őݒ肵���ʒu����Acontext.lineTo�Őݒ肵���ʒu�܂ł̐��������B
    // - �J�n����moveTo��lineTo�̒l�������ł��邽�߂����̓_�ƂȂ�B
    // - �h���b�O����lastPosition�ϐ��őO��̃}�E�X�ʒu���L�^���Ă��邽�߁A
    //   �O��̈ʒu���猻�݂̈ʒu�܂ł̐�(�_�̂Ȃ���)�ƂȂ�
    context.lineTo(x, y);

    // context.moveTo, context.lineTo�̒l�����Ɏ��ۂɐ�������
    context.stroke();

    // ���݂̃}�E�X�ʒu���L�^���āA������������Ƃ��̊J�n�_�Ɏg��
    lastPosition.x = x;
    lastPosition.y = y;
  }

  // canvas��ɏ������G��S������
  function clear() {
    context.clearRect(0, 0, canvas.width, canvas.height);
  }

  // �}�E�X�̃h���b�O���J�n������isDrag�̃t���O��true�ɂ���draw�֐�����
  // ���G�����������r���Ŏ~�܂�Ȃ��悤�ɂ���
  function dragStart(event) {
    // ���ꂩ��V�������������n�߂邱�Ƃ�錾����
    // ��A�̐��������������I��������dragEnd�֐�����closePath�ŏI����錾����
    context.beginPath();

    isDrag = true;
  }
  // �}�E�X�̃h���b�O���I��������A�������̓}�E�X��canvas�O�Ɉړ�������
  // isDrag�̃t���O��false�ɂ���draw�֐����ł��G�������������f�����悤�ɂ���
  function dragEnd(event) {
    // �������������̏I����錾����
    context.closePath();
    isDrag = false;

    // �`�撆�ɋL�^���Ă����l�����Z�b�g����
    lastPosition.x = null;
    lastPosition.y = null;
  }

  // �}�E�X�����{�^���N���b�N���̃C�x���g�������`����
  function initEventHandler() {
    const clearButton = document.querySelector('#clear-button');
    clearButton.addEventListener('click', clear);

    canvas.addEventListener('mousedown', dragStart);
    canvas.addEventListener('mouseup', dragEnd);
    canvas.addEventListener('mouseout', dragEnd);
    canvas.addEventListener('mousemove', (event) => {
      // event�̒��̒l���������ꍇ�͈ȉ��̂悤��console.log(event)�ŁA
      // �f�x���b�p�[�c�[���̃R���\�[���ɏo�͂�����Ɨǂ�
      // console.log(event);

      draw(event.layerX, event.layerY);
    });
  }

  // �C�x���g����������������
  initEventHandler();
});