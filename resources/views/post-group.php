<?php
    session_start();
    $_SESSION['js'] = 'group/app.js';
?>
<!DOCTYPE html>
<html>

<head>
    <?php
        @include __DIR__.'../../../template/meta.php';
    ?>
</head>

<body>
    <div id="app">
        <?php 
            @include __DIR__.'../../../template/header.php';
        ?>
        <div class="wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card m-b-20">
                            <div class="card-body">
                                <h4 class="mt-0 header-title">Đăng bài viết trên nhóm</h4>
                                <p class="text-muted m-b-30">Giúp bạn đăng bài viết trên tất cả nhóm bạn đang tham gia</p>
                                <form class="" action="#" novalidate="">
                                <div class="form-group">
                                    <label>Nhập Cookie</label>
                                    <div><textarea style="height:250px" v-model='input.cookie' class="form-control page-avoid-id" rows="5"></textarea></div>
                                </div>
                                <div class="clearfix"></div>
                                <div v-if="defaultValue.cookie">
                                    Chọn hình ảnh : <input multiple id="file" accept=".png,.jpg,.jpge" type="file">
                                    <br>
                                    <br>
                                    <div style="display:block !important" class="form-group">
                                        <label>Nội dung bài viết ( Cách nhau 1 dòng )</label>
                                        <div><textarea  v-model='input.content' class="form-control page-avoid-id" rows="5"></textarea></div>
                                    </div>
                                    <div class="form-group">
                                        <label>Nhóm</label>
                                        <input type="radio" @click="listGroupId = copyListGroupId" v-model="options.getGroupId" value="all" style="margin:5px;"> Tất cả
                                        <input type="radio" @click="listGroupId = copyListGroupId" v-model="options.getGroupId" value="custome" style="margin:5px;"> Tùy chọn
                                        <input type="radio" @click="listGroupId = []" v-model="options.getGroupId" value="list" style="margin:5px;"> Danh sách ID
                                    </div>
                                    <div v-if="options.getGroupId == 'custome' && copyListGroupId.length > 0">
                                        <table class="table table-bordered">
                                            <p class="alert alert-info">{{ customeListGroupId.length }} nhóm đã được chọn</p>
                                            <div class="row">
                                                <div class="col-12 input-group mb-3">
                                                    <input @keyup="searchGroup" type="text" class="form-control" placeholder="Tìm kiếm" aria-label="Username" aria-describedby="basic-addon1">
                                                </div>
                                            </div>
                                            <thead>
                                                <tr>
                                                    <th class="text-center" scope="col">STT</th>
                                                    <th style="width:200px" class="text-center" scope="col">Tên Nhóm</th>
                                                    <th class="text-center" scope="col">ID</th>
                                                    <th class="text-center" scope="col">Đã Đăng</th>
                                                    <th class="text-center" scope="col">Chọn <input v-model="allSelected" type="checkbox" @click="selectAll"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(group,index) in listGroupId" :key="index" v-if="index <= paginate">
                                                    <th class="text-center" scope="row">{{ ((current * paginate) + index + 1) - paginate  }}</th>
                                                    <td style="width:200px" class="text-center"><a target="_blank" :href="`https://facebook.com/${group.id}`">{{ group.name }}</td>
                                                    <td class="text-center">{{ group.id }}</td>
                                                    <td class="text-center"><i :style="{color:group.published ? '#5ec757' : '#e67070'}" :class="[group.published ? 'fas fa-check-circle' : 'fas fa-times']"></i></td>
                                                    <td class="text-center"><input v-model="customeListGroupId" :value="{id:group.id,name:group.name}" type="checkbox"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <nav aria-label="Page navigation example">
                                            <ul class="pagination">
                                                <li class="page-item" :class="[current == 1 ? 'disabled' : '']"><a class="page-link" @click="gotoPage(current - 1)">Trước</a></li>
                                                <li v-for="n in Math.round(copyListGroupId.length/paginate)" :key="n" class="page-item" :class="[n == current ? 'active' : '']"><a class="page-link" @click="gotoPage(n)">{{ n }}</a></li>
                                                <li class="page-item" :class="[current == Math.round(copyListGroupId.length/paginate) ? 'disabled' : '']"><a class="page-link" @click="gotoPage(current + 1)">Sau</a></li>
                                            </ul>
                                        </nav>
                                    </div>
                                    <div v-if="options.getGroupId == 'list'" class="form-group">
                                        <label>Danh sách ID nhóm ( Cách nhau 1 dòng )</label>
                                        <div><textarea v-model='input.groupId' class="form-control page-avoid-id" rows="5"></textarea></div>
                                    </div>
                                    <div class="form-group">
                                        <label>Thời gian nghỉ</label>
                                        <div><input v-model='input.sleep' data-parsley-type="number" type="text" class="form-control post-id" required="" placeholder=""></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div>
                                        <button :disabled="loading" type="submit" v-if="!defaultValue.cookie && !defaultValue.fb_dtsg" @click="request()" class="btn btn-primary waves-effect waves-light submit">Xác Thực Cookie</button> 
                                        <button :disabled="loading" type="submit" v-if="options.getGroupId == 'custome' && copyListGroupId.length > 0" @click="share(null,null,null,'post-group')" class="btn btn-primary waves-effect waves-light submit">Bắt Đầu ( {{ customeListGroupId.length }} )</button>
                                        <button :disabled="loading" type="submit" v-if="options.getGroupId !== 'custome' && defaultValue.cookie && defaultValue.fb_dtsg" @click="share(null,null,null,'post-group')" class="btn btn-primary waves-effect waves-light submit">Bắt Đầu</button>
                                    </div>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- end col -->
                    <div class="col-lg-4">
                        <div class="card m-b-20">
                            <div class="card-body">
                                <h4 class="mt-0 header-title">Thống Kê</h4>
                                <p class="loading-notice"></p>
                                <p class="data-response">
                                    <div class="row">
                                        <div class="col-12">
                                            <center>
                                                <img v-if="loading" width="50%" src="https://i.imgur.com/qQcTjCQ.gif">
                                            </center>
                                        </div>
                                        <div v-if="listSuccess.length > 0" class="col-12">
                                            <p>Thành Công : <strong>{{ listSuccess.length }}</strong></p>
                                            <div class='data-list'>
                                                <li style='list-style-type: none;background:#fff;padding:20px;border-radius:25px;margin:10px;' v-for="(success,index) in listSuccess" :key="index">
                                                    {{ index + 1 }}. <a target="_blank" :href="'https://facebook.com/' + success.group_id">{{ success.group_name }}</a> ( <a target="_blank" :href="'https://facebook.com/' + success.post_id">{{ success.post_id }}</a> ) - <span style="color:#2a8618">{{ success.msg }}</span>
                                                </li>
                                            </div>
                                        </div>
                                        <div v-if="listFail.length > 0" class="col-12">
                                            <p>Thất Bại : <strong>{{ listFail.length }}</strong></p>
                                            <div class='data-list'>
                                                <li style='list-style-type: none;background:#fff;padding:20px;border-radius:25px;margin:10px;' v-for="(fail,index) in listFail" :key="index">
                                                    {{ index + 1 }}.  <a target="_blank" :href="'https://facebook.com/' + fail.group_id">{{ fail.group_name }}</a> - <span style="color:#d64646">{{ fail.msg }} ( {{ fail.reason }} )</span>
                                                </li>
                                            </div>
                                        </div>
                                    </div>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end container -->
        </div>
        <!-- end wrapper -->
        <!-- Footer -->
        <?php
            @include __DIR__.'../../../template/footer.php';
        ?>

    </div>
</body>
<?php
    @include __DIR__.'../../../template/script.php';
?>

</html>