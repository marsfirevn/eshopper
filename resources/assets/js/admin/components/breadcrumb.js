import React from 'react';
import {Link} from 'react-router';

class Breadcrumb extends React.Component {
    constructor(props) {
        super(props);
    }

    render() {
        let items = this.props.items || [];

        if (items.length == 0) {
            return null;
        }

        let breadcrumbs = [],
            path = '';

        items.map((item, index) => {
            if (breadcrumbs.length < (items.length * 2 - 2)) {
                path = `${path}/${item.path}`;
                breadcrumbs.push(
                    <li key={index} className='breadcrumb-item'>
                        <Link to={path}>{item.name}</Link>
                    </li>
                );
            } else {
                breadcrumbs.push(
                    <li key={index} className='breadcrumb-item'>
                        <span>{item.name}</span>
                    </li>
                );
            }
        });

        return (
            <ul className={`breadcrumb ${this.props.className}`}>
                {breadcrumbs}
            </ul>
        );
    }
}

Breadcrumb.propsTypes = {
    items: React.PropTypes.object.isRequired
};

export default Breadcrumb;
