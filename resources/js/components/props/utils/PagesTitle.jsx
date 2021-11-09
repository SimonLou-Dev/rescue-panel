import React from 'react';
import axios from "axios";


class PagesTitle extends React.Component {
    constructor(props) {
        super(props);
        this.state = {}
    }

    render() {
        return (
            <div className={'title-contain'}>
                <h1 dangerouslySetInnerHTML={{__html: this.props.title}}/>
            </div>
        )
    }
}

export default PagesTitle;
