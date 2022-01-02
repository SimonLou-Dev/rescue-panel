import React from 'react';
import axios from "axios";


function Button(props) {
    return (
        <Button className={"btn"} onClick={props.callback}>
            {props.value}
        </Button>
    )
}

export default Button;
