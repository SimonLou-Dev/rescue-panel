import React from 'react'

class ATA extends React.Component{
    constructor(props) {
        super(props);
    }

    render() {
        return(
            <div className={'Rapport-Card'}>
                <h1>ATA</h1>
                <div className={'Form-Group ATA'}>
                    <label>Du</label>
                    <input type={'date'} value={this.props.startDate} onChange={(e)=>this.props.onStartDateChange(e.target.value)}/>
                    <label>à</label>
                    <input type="time" value={this.props.startTime} onChange={(e)=>this.props.onStartTimeChange(e.target.value)}/>
                </div>
                <div className={'Form-Group ATA'}>
                    <label>Au</label>
                    <input type={'date'} value={this.props.endDate} onChange={(e)=>this.props.onEndDateChange(e.target.value)}/>
                    <label>à</label>
                    <input type="time" value={this.props.endTime} onChange={(e)=>this.props.onEndTimeChange(e.target.value)}/>
                </div>
            </div>
        )
    }
}
export default ATA
