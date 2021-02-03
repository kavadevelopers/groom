<div class="page-header">
    <div class="row align-items-end">
        <div class="col-md-6">
            <div class="page-header-title">
                <div class="d-inline">
                    <h4><?= $_title ?></h4>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="row">
        <div class="col-md-12">
            <div class="cover-profile">
                <div class="profile-bg-img">
                    <img class="profile-bg-img img-fluid" src="<?= base_url() ?>asset/assets/images/user-profile/bg-img1.jpg" alt="bg-img">
                    <div class="card-block user-info">
                        <div class="col-md-12">
                            <div class="media-left">
                                <a href="javascript:;" class="profile-image">
                                    <img class="user-img img-radius" src="<?= base_url() ?>asset/images/user/<?= get_user()['gender'] == 'Male'?'male.png':'female.png' ?>" alt="user-img">
                                </a>
                            </div>
                            <div class="media-body row">
                                <div class="col-lg-12">
                                    <div class="user-title">
                                        <h2><?= get_user()['name'] ?></h2>
                                        <span class="text-white">
                                            Super Admin 
                                            <?php if(get_user()['user_type'] == 2 || get_user()['user_type'] == 3){ ?> 
                                                - <?= user_type() ?> 
                                            <?php } ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form method="post" action="<?= base_url('profile/save') ?>">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-block">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Name <span class="-req">*</span></label>
                                    <input name="name" type="text" placeholder="Name" class="form-control" value="<?= set_value('name',get_user()['name']); ?>">
                                    <?= form_error('name') ?>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Username <span class="-req">*</span></label>
                                    <input name="username" type="text" placeholder="Username" class="form-control" value="<?= set_value('username',get_user()['username']); ?>" readonly>
                                    <?= form_error('username') ?>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Password</label>
                                    <input name="password" type="text" placeholder="Password" class="form-control" value="<?= set_value('password'); ?>">
                                    <?= form_error('password') ?>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Mobile <span class="-req">*</span></label>
                                    <input name="mobile" type="text" placeholder="Mobile" class="form-control" value="<?= set_value('mobile',get_user()['mobile']); ?>">
                                    <?= form_error('mobile') ?>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Email <span class="-req">*</span></label>
                                    <input name="email" type="text" placeholder="Email" class="form-control" value="<?= set_value('email',get_user()['email']); ?>">
                                    <?= form_error('email') ?>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Gender <span class="-req">*</span></label>
                                    <select class="form-control" name="gender">
                                        <option value="Male" <?= set_value('gender',get_user()['gender']) == 'Male'?'selected':'' ?>>Male</option>
                                        <option value="Female" <?= set_value('gender',get_user()['gender']) == 'Female'?'selected':'' ?>>Female</option>
                                    </select>
                                    <?= form_error('gender') ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button class="btn btn-success" type="submit">
                            <i class="fa fa-save"></i> Save
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>