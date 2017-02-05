/**
 * Created by huukimit on 05/02/2017.
 */

import {white, grey700} from 'material-ui/styles/colors';

const mainColor = '#00BBE5';

export default {
    palette: {
        primary1Color: mainColor,
        primary2Color: mainColor,
        accent1Color: mainColor
    },
    appBar: {
        color: white,
        textColor: mainColor
    },
    flatButton: {
        secondaryTextColor: grey700
    },
    tableHeaderColumn: {
        textColor: white,
        height: '46px'
    },
    inkBar: {
        backgroundColor: white
    }
}
