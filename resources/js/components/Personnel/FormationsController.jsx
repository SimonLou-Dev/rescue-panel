import React from 'react';
import axios from "axios";
import PagesTitle from "../props/utils/PagesTitle";


class LivretFormation extends React.Component {
    render() {
        return (<div className="livret-page">
                <PagesTitle title={'Mon livret de formation'}/>
            <div className={'livret'}>
                <div className="livret-content">
                    <section className={'left'}>
                        <div className={'forma'}>
                            <div className="infos">
                                <img src={"https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQAHAZwm2bk1snFy8D03Z3-a_VXXAMx33a42g&usqp=CAU"} alt={""}/>
                                <div className="text">
                                    <h5>BC - LifeGuard</h5>
                                    <p>Habenas mori, tanquam altus impositio. Cadunt nunquam ducunt ad raptus abactus. Detrius, cannabis, et fluctus. Ubi est bassus gemna?</p>
                                </div>
                            </div>
                            <div className="validation">
                                <h3>résulat</h3>
                                <img src={'https://as2.ftcdn.net/jpg/00/20/19/65/500_F_20196541_1AaZysgM7wGN4HyYeXH1XCjVLLPELIWC.jpg'} alt={''}/>
                            </div>
                        </div>
                        <div className={'forma'}>
                            <div className="infos">
                                <img src={"https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQAHAZwm2bk1snFy8D03Z3-a_VXXAMx33a42g&usqp=CAU"} alt={""}/>
                                <div className="text">
                                    <h5>BC - LifeGuard</h5>
                                    <p>Habenas mori, tanquam altus impositio. Cadunt nunquam ducunt ad raptus abactus. Detrius, cannabis, et fluctus. Ubi est bassus gemna?</p>
                                </div>
                            </div>
                            <div className="validation">
                                <h3>résulat</h3>
                                <img src={'https://as2.ftcdn.net/jpg/00/20/19/65/500_F_20196541_1AaZysgM7wGN4HyYeXH1XCjVLLPELIWC.jpg'} alt={''}/>
                            </div>
                        </div>
                    </section>
                    <section className={'right'}>
                        <div className={'forma'}>
                            <div className="infos">
                                <img src={"https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQAHAZwm2bk1snFy8D03Z3-a_VXXAMx33a42g&usqp=CAU"} alt={""}/>
                                <div className="text">
                                    <h5>BC - LifeGuard</h5>
                                    <p>Habenas mori, tanquam altus impositio. Cadunt nunquam ducunt ad raptus abactus. Detrius, cannabis, et fluctus. Ubi est bassus gemna?</p>
                                </div>
                            </div>
                            <div className="validation">
                                <h3>résulat</h3>
                                <img src={'https://as2.ftcdn.net/jpg/00/20/19/65/500_F_20196541_1AaZysgM7wGN4HyYeXH1XCjVLLPELIWC.jpg'} alt={''}/>
                            </div>
                        </div>
                        <div className={'forma'}>
                            <div className="infos">
                                <img src={"https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQAHAZwm2bk1snFy8D03Z3-a_VXXAMx33a42g&usqp=CAU"} alt={""}/>
                                <div className="text">
                                    <h5>BC - LifeGuard</h5>
                                    <p>Habenas mori, tanquam altus impositio. Cadunt nunquam ducunt ad raptus abactus. Detrius, cannabis, et fluctus. Ubi est bassus gemna?</p>
                                </div>
                            </div>
                            <div className="validation">
                                <h3>résulat</h3>
                                <img src={'https://as2.ftcdn.net/jpg/00/20/19/65/500_F_20196541_1AaZysgM7wGN4HyYeXH1XCjVLLPELIWC.jpg'} alt={''}/>
                            </div>
                        </div>
                    </section>
                </div>
                <div className="livret-footer">
                    <button className={'btn'}>Page précédente</button>
                    <button className={'btn'}>Page suivante</button>
                </div>
            </div>
        </div>);
    }
}

class ResponsePage extends React.Component {
    render() {
        return null;
    }
}

class FormationsController extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            status: null,
        }
    }

    render() {
        switch (this.state.status){
            case null:
                return (<LivretFormation/>)
            case 1:
                return (<ResponsePage/>)
        }
    }
}



export default FormationsController;
