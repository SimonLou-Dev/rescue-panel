import React from 'react';
import axios from "axios";
import CardComponent from "../../../props/CardComponent";


function Rapport(props) {
    return (
        <div className={"rapports"}>
            <div className={'collumn'}>
                <CardComponent title={'Patient'}>
                    <div className={'form-item form-column'}>
                        <label>test</label>
                        <input type={'text'} className={'form-input'}/>
                        <div className={'errors-list'}>
                            <ul>
                                <li>test</li>
                            </ul>
                        </div>
                    </div>
                    <div className={'form-item form-column'}>
                        <label>test</label>
                        <input type={'text'} className={'form-input'}/>
                        <div className={'errors-list'}>
                            <ul>
                                <li>test</li>
                            </ul>
                        </div>
                    </div>
                    <div className={'form-item form-column'}>
                        <label>test</label>
                        <input type={'text'} className={'form-input'}/>
                        <div className={'errors-list'}>
                            <ul>
                                <li>test</li>
                            </ul>
                        </div>
                    </div>
                    <div className={'form-item form-column'}>
                        <label>test</label>
                        <input type={'text'} className={'form-input'}/>
                        <div className={'errors-list'}>
                            <ul>
                                <li>test</li>
                            </ul>
                        </div>
                    </div>
                </CardComponent>
            </div>
            <div className={'collumn'}>
                <CardComponent title={'Intervention'}>
                    <div className={'form-item form-column'}>
                        <label>test</label>
                        <input type={'text'} className={'form-input'}/>
                        <input type={'text'} className={'form-input'}/>
                        <div className={'errors-list'}>
                            <ul>
                                <li>test</li>
                            </ul>
                        </div>
                    </div>
                    <div className={'form-item form-column'}>
                        <label>test</label>
                        <input type={'text'} className={'form-input'}/>
                        <div className={'errors-list'}>
                            <ul>
                                <li>test</li>
                            </ul>
                        </div>
                    </div>
                    <div className={'form-item form-column'}>
                        <label>test</label>
                        <input type={'text'} className={'form-input'}/>
                        <div className={'errors-list'}>
                            <ul>
                                <li>test</li>
                            </ul>
                        </div>
                    </div>
                </CardComponent>
            </div>
            <div className={'collumn'}>
                <CardComponent title={'ATA'}>
                    <div className={'form-item form-column'}>
                        <label>test</label>
                        <input type={'text'} className={'form-input'}/>
                        <label className={'form-healper'}>ex: 13h 3m</label>
                        <div className={'errors-list'}>
                            <ul>
                                <li>test</li>
                            </ul>
                        </div>
                    </div>
                </CardComponent>
                <CardComponent title={'Facturation'}>
                    <div className={'form-item form-column'}>
                        <label>test</label>
                        <input type={'text'} className={'form-input'}/>
                        <div className={'errors-list'}>
                            <ul>
                                <li>test</li>
                            </ul>
                        </div>
                    </div>
                </CardComponent>
            </div>
            <div className={'collumn'}>
                <CardComponent title={'Transport'}>
                    <div className={'form-item form-column'}>
                        <label>test</label>
                        <input type={'text'} className={'form-input'}/>
                        <div className={'errors-list'}>
                            <ul>
                                <li>test</li>
                            </ul>
                        </div>
                    </div>
                </CardComponent>
                <CardComponent title={'Description'}>
                    <div className={'form-item form-column'}>
                        <label>test</label>
                        <textarea className={'form-input'} rows={10}/>
                        <div className={'errors-list'}>
                            <ul>
                                <li>test</li>
                            </ul>
                        </div>
                    </div>
                </CardComponent>
            </div>
        </div>
    )
}

export default Rapport;
