import React from 'react';
import autobind from 'react-autobind';
import {Paper, Menu, MenuItem, Divider} from 'material-ui';
// svg-icons
import RemoveRedEye from 'material-ui/svg-icons/image/remove-red-eye';
import PersonAdd from 'material-ui/svg-icons/social/person-add';
import ContentLink from 'material-ui/svg-icons/content/link';
import ContentCopy from 'material-ui/svg-icons/content/content-copy';
import Download from 'material-ui/svg-icons/file/file-download';
import Delete from 'material-ui/svg-icons/action/delete';

class Navigation extends React.Component {
    constructor(props) {
        super(props);
        autobind(this);
    }

    render() {
        return (
            <Paper className="nav-wrapper" zDepth={0}>
                <div className="nav">
                    <div className="logo">
                        <div className="logo-image"/>
                    </div>

                    <div className="menu">
                        <Menu className="main-menu">
                            <MenuItem primaryText="Preview" leftIcon={<RemoveRedEye />}/>
                            <MenuItem primaryText="Share" leftIcon={<PersonAdd />}/>
                            <MenuItem primaryText="Get links" leftIcon={<ContentLink />}/>
                            <Divider />
                            <MenuItem primaryText="Make a copy" leftIcon={<ContentCopy />}/>
                            <MenuItem primaryText="Download" leftIcon={<Download />}/>
                            <Divider />
                            <MenuItem primaryText="Remove" leftIcon={<Delete />}/>
                        </Menu>
                    </div>
                </div>
            </Paper>
        );
    }
}

export default Navigation;
