import React from 'react';
import autobind from 'react-autobind';
import {Paper, Table, TableHeader, TableBody, TableRow, TableHeaderColumn, TableRowColumn} from 'material-ui';
import $ from 'jquery';

class AdminList extends React.Component {
    constructor(props) {
        super(props);
        autobind(this);
    }

    renderRow(admin, index) {
        return (
            <TableRow key={index}>
                <TableRowColumn>{index + 1}</TableRowColumn>
                <TableRowColumn>{Helper.getUserName(admin)}</TableRowColumn>
                <TableRowColumn>{admin.email}</TableRowColumn>
                <TableRowColumn>{admin.is_active ? <span>Actived</span> : null}</TableRowColumn>
                <TableRowColumn className="actions">
                    <span>View</span>
                    <span>Edit</span>
                    <span>Remove</span>
                </TableRowColumn>
            </TableRow>
        );
    }

    render() {
        let admins = this.props.admins;
        let tableHeight = window.innerHeight - 218;

        return (
            <Paper zDepth={0}>
                <div className="page-header">
                    <h2 className="page-title">Admin list</h2>
                </div>

                <section className="content list">
                    <div className="table">
                        <Table selectable={false} height={`${tableHeight}px`} fixedHeader={true}>
                            <TableHeader className="table-header" displaySelectAll={false} adjustForCheckbox={false}>
                                <TableRow>
                                    <TableHeaderColumn>#</TableHeaderColumn>
                                    <TableHeaderColumn>Name</TableHeaderColumn>
                                    <TableHeaderColumn>Email</TableHeaderColumn>
                                    <TableHeaderColumn>Active</TableHeaderColumn>
                                    <TableHeaderColumn>Action</TableHeaderColumn>
                                </TableRow>
                            </TableHeader>

                            <TableBody displayRowCheckbox={false} showRowHover={false} stripedRows={true}>
                                {$.map(admins, (admin, index) => this.renderRow(admin, index))}
                            </TableBody>
                        </Table>
                    </div>
                </section>
            </Paper>
        );
    }
}

AdminList.contextTypes = {
    app: React.PropTypes.object,
    auth: React.PropTypes.object
};

export default AdminList;
