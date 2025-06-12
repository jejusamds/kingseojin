<?php
include $_SERVER['DOCUMENT_ROOT'] . "/Madmin/inc/top.php";
?>

<link rel="stylesheet" href="/js/jstree/dist/themes/default/style.min.css" />
<script src="/js/jstree/dist/jstree.min.js"></script>

<div class="pageWrap">
    <div class="page-heading">
        <h3>카테고리 관리</h3>
        <ul class="breadcrumb">
            <li>제품 관리</li>
            <li class="active">카테고리 관리</li>
        </ul>
    </div>

    <div class="box comMTop20 comFLeft" style="width:400px;">
        <div class="panel">
            <div id="jstree"></div>
        </div>
    </div>

    <div class="box comMTop20 comFLeft" style="width:380px; margin-left:20px;">
        <div class="panel">
            <div id="category_image" style="text-align:center; padding:20px;">
                <img id="category_img_preview" src="/img/product/product_sub01_slide_img_con_none_img.png"
                    alt="카테고리 이미지" style="max-width:100%; height:auto; border:1px solid #ddd; padding:5px;" />
                <p id="category_caption" style="margin:10px 0; color:#666;">
                    카테고리를 선택하세요
                </p>
                <div>
                    <input type="file" id="category_file_input" accept="image/*"
                        style="margin:0 auto 10px; width: 200px;" disabled />
                    <span>(690px * 690px)</span>
                </div>
                <button id="category_upload_btn" class="btn btn-primary btn-sm">이미지 업로드</button>
                <button id="category_delete_btn" class="btn btn-danger btn-sm" disabled
                    style="margin-left:10px;">삭제</button>
            </div>
        </div>
    </div>

</div>

<script>

    var selectedCategoryId = null;


    $(function () {
        $('#jstree').jstree({
            core: {
                data: {
                    url: 'category_save.php',
                    dataType: 'json',
                    // always send mode=get when loading the tree
                    data: function (node) {
                        return { mode: 'get' };
                    }
                },
                check_callback: true,
                themes: { stripes: true }
            },
            plugins: ['contextmenu', 'dnd', 'wholerow'],
            contextmenu: {
                items: function (node) {
                    return {
                        Create: {
                            label: "추가",
                            action: function () {
                                $('#jstree').jstree('create_node',
                                    node,
                                    { text: '새 카테고리' },
                                    'last',
                                    function (n) {
                                        $('#jstree').jstree('edit', n);
                                    }
                                );
                            }
                        },
                        Rename: {
                            label: "이름변경",
                            action: function () {
                                $('#jstree').jstree('edit', node);
                            }
                        },
                        Delete: {
                            label: "삭제",
                            action: function () {
                                if (confirm('이 카테고리를 삭제하시겠습니까?')) {
                                    $('#jstree').jstree('delete_node', node);
                                }
                            }
                        }
                    };
                }
            }
        })
            .on('ready.jstree', function () {
                var tree = $(this).jstree(true);
                tree.get_node('#').children.forEach(function (childId) {
                    //tree.open_node(childId);
                });
            })

            // ───────────────────────────────────
            // create
            // ───────────────────────────────────
            .on('create_node.jstree', function (e, data) {
                $.post('category_save.php',
                    {
                        mode: 'create',
                        parent: data.node.parent === '#' ? null : data.node.parent,
                        text: data.node.text
                    },
                    function (res) {
                        if (res.success) {
                            $('#jstree').jstree('set_id', data.node, res.id);
                        } else {
                            console.log('Create failed:', res.error);
                            $('#jstree').jstree('refresh');
                        }
                    },
                    'json'
                )
                    .fail(function (xhr, status, err) {
                        console.log('Create AJAX error:', status, err);
                        $('#jstree').jstree('refresh');
                    });
            })

            // ───────────────────────────────────
            // rename
            // ───────────────────────────────────
            .on('rename_node.jstree', function (e, data) {
                $.post('category_save.php',
                    {
                        mode: 'rename',
                        id: data.node.id,
                        text: data.text
                    },
                    function (res) {
                        if (!res.success) {
                            console.log('Rename failed:', res.error);
                            $('#jstree').jstree('refresh');
                        }
                    },
                    'json'
                )
                    .fail(function (xhr, status, err) {
                        console.log('Rename AJAX error:', status, err);
                        $('#jstree').jstree('refresh');
                    });
            })

            // ───────────────────────────────────
            // delete
            // ───────────────────────────────────
            .on('delete_node.jstree', function (e, data) {
                console.log(data);
                $.post('category_save.php',
                    {
                        mode: 'delete',
                        id: data.node.id
                    },
                    function (res) {
                        console.log(res);
                        if (!res.success) {
                            console.log('Delete failed:', res.error);
                            $('#jstree').jstree('refresh');
                        }
                    },
                    'json'
                )
                    .fail(function (e) {
                        console.log(e);
                        $('#jstree').jstree('refresh');
                    });
            })

            // ───────────────────────────────────
            // move (drag & drop)
            // ───────────────────────────────────
            .on('move_node.jstree', function (e, data) {

                $.post('category_save.php',
                    {
                        mode: 'move',
                        id: data.node.id,
                        parent: data.parent === '#' ? null : data.parent,
                        position: data.position
                    },
                    function (res) {
                        console.log(res);
                        if (!res.success) {
                            console.log('Move failed:', res.error);
                            $('#jstree').jstree('refresh');
                        }
                    },
                    'json'
                )
                    .fail(function (xhr, status, err) {
                        console.log('Move AJAX error:', status, err);
                        $('#jstree').jstree('refresh');
                    });
            })

            .on('select_node.jstree', function (e, data) {
                selectedCategoryId = data.node.id;

                // 비활성화 & 플레이스홀더
                //$('#category_file_input, #category_upload_btn').prop('disabled', true);
                $('#category_img_preview').attr('src', '/img/product/product_sub01_slide_img_con_none_img.png');
                $('#category_caption').text('로딩 중…');
                $('#category_file_input, #category_upload_btn, #category_delete_btn').prop('disabled', true);

                // AJAX로 depth,name,thumbnail 가져오기
                $.getJSON('category_save.php', { mode: 'get_image', id: selectedCategoryId })
                    .done(function (res) {
                        if (!res.success) {
                            $('#category_caption').text(res.error);
                            return;
                        }
                        // 미리보기
                        var url = res.thumbnail
                            ? '/userfiles/contents/category/' + res.thumbnail
                            : '/img/product/product_sub01_slide_img_con_none_img.png';
                        $('#category_img_preview').attr('src', url);
                        $('#category_caption').text(res.name);

                        // depth 1·2차면 업로드 허용
                        if (res.depth <= 2) {
                            $('#category_file_input, #category_upload_btn').prop('disabled', false);
                        } else {
                            $('#category_caption').text('3차 카테고리는 이미지 업로드 불가');
                        }

                        // 이미지가 있으면 삭제 버튼 활성화
                        if (res.thumbnail) {
                            $('#category_delete_btn').prop('disabled', false);
                        }
                    })
                    .fail(function (xhr, status, err) {
                        console.log('get_image AJAX error:', status, err);
                        $('#category_caption').text('이미지 로드 실패');
                    });
            });



        // 3) 파일 선택시: 클라이언트 미리보기
        $('#category_file_input').on('change', function () {
            var file = this.files[0];
            if (!file) return;
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#category_img_preview').attr('src', e.target.result);
            };
            reader.readAsDataURL(file);
        });

        // 4) 업로드 버튼 클릭
        $('#category_upload_btn').on('click', function () {
            if (!selectedCategoryId) {
                alert('카테고리를 먼저 선택해 주세요.');
                return;
            }

            if (this.disabled) return;

            var fileInput = $('#category_file_input')[0];
            if (!fileInput.files[0]) {
                alert('업로드할 이미지를 선택해 주세요.');
                return;
            }

            var formData = new FormData();
            formData.append('mode', 'upload');
            formData.append('id', selectedCategoryId);
            formData.append('thumbnail', fileInput.files[0]);

            $.ajax({
                url: 'category_save.php',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json'
            })
                .done(function (res) {
                    if (res.success) {
                        // 1) 서버 저장된 실제 URL로 미리보기 업데이트
                        $('#category_img_preview').attr('src', res.url);
                        // 2) 파일 입력창 초기화
                        $('#category_file_input').val('');
                        // 3) 업로드 버튼 비활성화 (파일 없어야만 활성)
                        $('#category_upload_btn').prop('disabled', true);
                        // 4) 삭제 버튼 활성화
                        $('#category_delete_btn').prop('disabled', false);
                        alert('업로드 되었습니다.');
                    } else {
                        alert('업로드 실패: ' + res.error);
                    }
                })
                .fail(function (xhr, status, err) {
                    console.log('upload AJAX error:', status, err);
                    alert('업로드 중 오류가 발생했습니다.');
                });
        });


        // ───────────────────────────────────
        // 썸네일 삭제
        // ───────────────────────────────────
        $('#category_delete_btn').on('click', function () {
            if (!selectedCategoryId) return;
            if (!confirm('이미지를 정말 삭제하시겠습니까?')) return;

            $.post('category_save.php', {
                mode: 'delete_image',
                id: selectedCategoryId
            }, function (res) {
                if (res.success) {
                    // UI 초기화
                    $('#category_img_preview').attr('src', '/img/product/product_sub01_slide_img_con_none_img.png');
                    $('#category_caption').text('이미지가 삭제되었습니다.');
                    $('#category_file_input, #category_upload_btn, #category_delete_btn')
                        .prop('disabled', true)
                        .val(''); // 파일 input 클리어
                } else {
                    alert('삭제 실패: ' + res.error);
                }
            }, 'json')
                .fail(function (xhr, status, err) {
                    console.log('delete_image AJAX error:', status, err);
                    alert('삭제 중 오류가 발생했습니다.');
                });
        });

    });
</script>

<?php include $_SERVER['DOCUMENT_ROOT'] . "/Madmin/inc/bottom.php"; ?>