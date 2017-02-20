import React from 'react';
import autobind from 'react-autobind';
import {Paper, TextField, RaisedButton, Avatar} from 'material-ui';
import Config from '../../../config';
import $ from 'jquery';
import toastr from 'toastr';

class AdminProfile extends React.Component {
    constructor(props) {
        super(props);
        autobind(this);

        this.state = {
            currentAvatarData: null,
            currentAvatarFile: null
        };
    }

    onSubmit() {
        let formData = new FormData(document.getElementById('profile-form'));
        formData.append('_method', 'PUT');

        $.ajax({
            url: '/api/profile',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: (response) => {
                toastr.success(response.message);
                this.context.app.onAuthUpdated(response.auth);
                this.clearPassword();
            },
            error: (errors) => {
                toastr.error(Helper.getFirstError(errors));
            }
        });
    }

    onCancel() {
        let form = document.getElementById('profile-form');
        form.reset();
        this.setState({
            currentAvatarData: null,
            currentAvatarFile: null
        });
    }

    handleFileChange(event) {
        let file = event.target.files[0];
        let fileReader = new FileReader();
        fileReader.readAsDataURL(file);
        fileReader.onloadend = (event) => {
            this.setState({
                currentAvatarData: event.target.result,
                currentAvatarFile: file
            });
        };
    }

    openSelectFile() {
        $('#avatar').click();
    }

    clearPassword() {
        this.refs.password.input.value = '';
        this.refs.password_confirmation.input.value = '';
    }

    render() {
        let profile = this.context.auth;

        return (
            <Paper zDepth={0} className="form">
                <div className="page-header">
                    <h2 className="page-title">Your profile</h2>
                </div>

                <section className="content">
                    <form id="profile-form" action="javascript:void(0);" method="post">
                        <div className="row basic-profile">
                            <div className="col-md-3">
                                <Avatar
                                    className='avatar'
                                    src={this.state.currentAvatarData || profile.avatar || Config.defaultAvatar}
                                    onClick={this.openSelectFile}
                                />
                                <input
                                    id="avatar"
                                    name="avatar"
                                    type="file"
                                    style={{display: 'none'}}
                                    onChange={this.handleFileChange}
                                />
                            </div>

                            <div className="col-md-9">
                                <div className="form-profile">
                                    <div className="row">
                                        <div className="col-md-12">
                                            <div className="title">
                                                <label htmlFor="email">Email</label>
                                            </div>
                                            <TextField
                                                className="input-border"
                                                name="email"
                                                type="text"
                                                value={profile.email}
                                                disabled={true}
                                                fullWidth={true}
                                                errorStyle={{textAlign: 'left'}}
                                            />
                                        </div>
                                    </div>

                                    <div className="row">
                                        <div className="col-md-6 input-first-name">
                                            <div className="title">
                                                <label htmlFor="first_name">First Name</label>
                                            </div>
                                            <TextField
                                                id='first_name'
                                                name="first_name"
                                                ref='first_name'
                                                className='input-border'
                                                hintText='Enter first name'
                                                fullWidth={true}
                                                defaultValue={profile.first_name}
                                            />
                                        </div>
                                        <div className="col-md-6 input-last-name">
                                            <div className="title">
                                                <label htmlFor="last_name">Last name</label>
                                            </div>
                                            <TextField
                                                id='last_name'
                                                name="last_name"
                                                ref='last_name'
                                                hintText='Enter last name'
                                                className='input-border'
                                                fullWidth={true}
                                                defaultValue={profile.last_name}
                                            />
                                        </div>
                                    </div>

                                    <div className="row">
                                        <div className="col-md-6">
                                            <div className="title">
                                                <label htmlFor="password">New password</label>
                                            </div>
                                            <TextField
                                                type="password"
                                                id='password'
                                                ref="password"
                                                name="password"
                                                hintText='Enter current password'
                                                className='input-border'
                                                fullWidth={true}
                                                defaultValue={profile.password || ''}
                                            />
                                        </div>
                                        <div className="col-md-6">
                                            <div className="title">
                                                <label htmlFor="password_confirmation">Confirm password</label>
                                            </div>
                                            <TextField
                                                type="password"
                                                id='password_confirmation'
                                                ref="password_confirmation"
                                                name="password_confirmation"
                                                hintText='Re enter new password'
                                                className='input-border'
                                                fullWidth={true}
                                                defaultValue={this.state.password_confirmation || ''}
                                            />
                                        </div>
                                    </div>

                                    <div className="row">
                                        <div className="col-md-12">
                                            <RaisedButton
                                                label="Save"
                                                primary={true}
                                                style={{marginRight: 24}}
                                                onClick={this.onSubmit}
                                            />
                                            <RaisedButton
                                                label="Cancel"
                                                primary={false}
                                                onClick={this.onCancel}
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </section>
            </Paper>
        );
    }
}

AdminProfile.contextTypes = {
    app: React.PropTypes.object,
    auth: React.PropTypes.object
};

export default AdminProfile;
