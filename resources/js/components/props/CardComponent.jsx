import React from 'react';
import axios from "axios";


function CardComponent(props) {
    return (
        <div className={"Card"}>
            <section className={"card-header"}>
                <h3>{props.title}</h3>
            </section>
            <section className={"card-content"}>
                {props.children}
            </section>
        </div>
    )
}

export default CardComponent;
