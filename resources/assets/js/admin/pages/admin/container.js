import React from 'react';
import autobind from 'react-autobind';
import AdminList from './admin-list';
import $ from 'jquery';
import toastr from 'toastr';

class AdminContainer extends React.Component {
    constructor(props) {
        super(props);
        autobind(this);

        this.state = {
            admins: []
        };
    }

    componentWillMount() {
        this.getAdminList();
    }

    getAdminList() {
        let admins = this.context.app.getStore('admins');
        if (admins) {
            this.setState({admins});
        } else {
            $.ajax({
                url: '/api/admins',
                type: 'GET',
                success: (response) => {
                    admins = response.admins;
                    this.context.app.updateStore('admins', admins);
                    this.setState({admins});
                },
                error: (errors) => {
                    toastr.error(Helper.getFirstError(errors));
                }
            });
        }
    }

    render() {
        if (!this.state.admins) {
            return null;
        }

        return (
            <div className="module-container">
                <AdminList component={AdminList} admins={this.state.admins}/>
            </div>
        );
    }
}

AdminContainer.contextTypes = {
    app: React.PropTypes.object,
    auth: React.PropTypes.object
};

export default AdminContainer;
