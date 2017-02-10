import React from 'react';
import autobind from 'react-autobind';
import {AppBar, Popover, Menu, MenuItem, FlatButton} from 'material-ui';

class Header extends React.Component {
    constructor(props) {
        super(props);
        autobind(this);

        this.state = {
            menuOpen: false
        };
    }

    toggleMenu() {
        let menuOpen = !this.state.menuOpen;
        this.setState({menuOpen});
    }

    closeMenu() {
        this.setState({menuOpen: false});
    }

    goToProfile() {
        this.props.router.push('/update-profile');
    }

    render() {
        let admin = this.context.auth;

        return (
            <AppBar className="header" iconElementLeft={<div/>} zDepth={0}>
                <div className="header-content">
                    <div className="left-header">
                        {this.props.children}
                    </div>
                    <div className="header-menu" ref="userMenu">
                        <FlatButton
                            className="btn-toggle-menu"
                            label={Helper.getUserName(admin)}
                            onClick={this.toggleMenu}
                        />
                        <Popover
                            className="user-menu-wrapper"
                            open={this.state.menuOpen}
                            anchorEl={this.refs.userMenu}
                            anchorOrigin={{horizontal: 'right', vertical: 'bottom'}}
                            targetOrigin={{horizontal: 'right', vertical: 'top'}}
                            onRequestClose={this.closeMenu}
                        >
                            <Menu>
                                <MenuItem
                                    primaryText="Update profile"
                                    innerDivStyle={{paddingLeft: '50px'}}
                                    style={{cursor: 'pointer'}}
                                    leftIcon={
                                        <i className="fa fa-key" style={{margin: '14px'}} aria-hidden="true"/>
                                    }
                                    onClick={this.goToProfile}
                                />
                                <MenuItem
                                    primaryText="Log out"
                                    innerDivStyle={{paddingLeft: '50px'}}
                                    href="/logout"
                                    leftIcon={
                                        <i className="fa fa-sign-out" style={{margin: '14px'}} aria-hidden="true"/>
                                    }
                                />
                            </Menu>
                        </Popover>
                    </div>
                </div>
            </AppBar>
        );
    }
}

Header.contextTypes = {
    app: React.PropTypes.object,
    auth: React.PropTypes.object
};

export default Header;
