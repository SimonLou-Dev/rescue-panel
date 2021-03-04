import React from 'react';

class InterventionItem extends React.Component {
    constructor(props) {
        super(props);
        this.OnClicked = this.OnClicked.bind(this);
    }

    OnClicked(event){
        this.props.CallBack(this.props.id);
    }

    render() {
        return (
            <div className={'InterventionItem'}>
                <button onClick={this.OnClicked}>{this.props.inter}</button>
            </div>
        )
    }
}

export default InterventionItem;
