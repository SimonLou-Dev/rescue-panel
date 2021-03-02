import React from 'react';


class PersonnelCard extends React.Component{
    constructor(props) {
        super(props);
    }
    render() {
        return(
            <div className={"Personnel-card"}>
                <h5>{this.props.name}</h5>
            </div>
        );
    }
}
export default PersonnelCard;
