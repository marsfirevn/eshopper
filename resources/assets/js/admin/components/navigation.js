import React from 'react';
import autobind from 'react-autobind';
import {Drawer, Menu, MenuItem, Divider} from 'material-ui';
import {withRouter} from 'react-router';
import Config from '../../config';

class Navigation extends React.Component {
    constructor(props) {
        super(props);
        autobind(this);

        this.state = {
            open: true
        };
    }

    goTo(path) {
        this.props.router.push(path);
    }

    render() {
        let open = this.state.open;

        return (
            <Drawer className="nav" zDepth={2} open={open}>
                <div className="logo">
                    <div className="logo-image">{Config.webName}</div>
                </div>

                <div className="main-menu-wrapper">
                    <Menu className="main-menu">
                        <MenuItem primaryText="Dashboard" onTouchTap={this.goTo.bind(this, '/dashboard')}/>
                        <Divider/>
                        <MenuItem primaryText="Order" onTouchTap={this.goTo.bind(this, '/orders')}/>
                        <MenuItem primaryText="Product" onTouchTap={this.goTo.bind(this, '/products')}/>
                        <MenuItem primaryText="Category" onTouchTap={this.goTo.bind(this, '/categories')}/>
                        <Divider/>
                        <MenuItem primaryText="Customer" onTouchTap={this.goTo.bind(this, '/customers')}/>
                        <MenuItem primaryText="Admin" onTouchTap={this.goTo.bind(this, '/admins')}/>
                        <Divider/>
                    </Menu>
                </div>
            </Drawer>
        );
    }
}

export default withRouter(Navigation);
