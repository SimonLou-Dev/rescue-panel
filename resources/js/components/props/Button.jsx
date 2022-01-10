import React from 'react';
import axios from "axios";


function Button(props) {
    return (
        <button className={"btn"} onClick={props.callback}>
            {props.value}
        </button>
    )
}

export default Button;
