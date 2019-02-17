/*
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 */

/*
 * The sample smart contract for documentation topic:
 * Writing Your First Blockchain Application
 */

package main

/* Imports
 * 4 utility libraries for formatting, handling bytes, reading and writing JSON, and string manipulation
 * 2 specific Hyperledger Fabric specific libraries for Smart Contracts
 */
import (
	"bytes"
	"encoding/json"
	"fmt"
	"strconv"
	"time"

	//"strconv"

	"github.com/hyperledger/fabric/core/chaincode/shim"
	sc "github.com/hyperledger/fabric/protos/peer"
)

// Define the Smart Contract structure
type SmartContract struct {
}

// Define the car structure, with 4 properties.  Structure tags are used by encoding/json library
type User struct {
	Id       string `json:"id"`
	Name     string `json:"name"`
	Password string `json:"password"`
}

type Nomination struct {
	IdFrom   string `json:"idfrom"`
	IdTo     string `json:"idto"`
	Position string `json:"position"`
}

/*
 * The Init method is called when the Smart Contract "fabcar" is instantiated by the blockchain network
 * Best practice is to have any Ledger initialization in separate function -- see initLedger()
 */

func (s *SmartContract) Init(APIstub shim.ChaincodeStubInterface) sc.Response {
	return shim.Success(nil)
}

/*
 * The Invoke method is called as a result of an application request to run the Smart Contract "fabcar"
 * The calling application program has also specified the particular smart contract function to be called, with arguments
 */
func (s *SmartContract) Invoke(APIstub shim.ChaincodeStubInterface) sc.Response {

	// Retrieve the requested Smart Contract function and arguments
	function, args := APIstub.GetFunctionAndParameters()
	// Route to the appropriate handler function to interact with the ledger appropriately
	if function == "initLedger" {
		return s.initLedger(APIstub)
	} else if function == "queryUser" {
		return s.queryUser(APIstub, args)
	} else if function == "createUser" {
		return s.createUser(APIstub, args)
	} else if function == "queryAllUsers" {
		return s.queryAllUsers(APIstub)
	} else if function == "nominate" {
		return s.nominate(APIstub, args)
	} else if function == "getNominationList" {
		return s.getNominationList(APIstub, args)
	} else if function == "getTimestamps" {
		return s.getTimestamps(APIstub)
	} else if function == "setTimePosition" {
		return s.setTimePosition(APIstub, args)
	} else if function == "whichTime" {
		return s.whichTime(APIstub)
	}

	return shim.Error("Invalid Smart Contract function name.")
}

func (s *SmartContract) setTimePosition(APIstub shim.ChaincodeStubInterface, args []string) sc.Response {

	for index, value := range args {

		valueAsBytes, _ := json.Marshal(value)

		var dataType string
		var _index int

		//first three are timestamps
		if index < 3 {
			dataType = "TIME"
			_index = index
		} else {
			dataType = "POSITION"
			_index = index - 3
		}

		APIstub.PutState(dataType+strconv.Itoa(_index), valueAsBytes)
	}

	return shim.Success(nil)
}

//for testing
func (s *SmartContract) getTimestamps(APIstub shim.ChaincodeStubInterface) sc.Response {

	timeAsBytes, err := APIstub.GetState("TIME0")
	if err != nil {
		return shim.Error("Error while fetching time")
	}
	if len(timeAsBytes) == 0 {
		//TODO
		return shim.Error("Time isn't added yet")
	}

	return shim.Success(timeAsBytes)
}

func (s *SmartContract) initLedger(APIstub shim.ChaincodeStubInterface) sc.Response {
	users := []User{
		User{Id: "1234", Name: "Hasnayeen", Password: "1234"},
		User{Id: "2345", Name: "Shuhan", Password: "1234"},
		User{Id: "0000", Name: "bashar", Password: "1234"},
		User{Id: "0001", Name: "kamrul", Password: "1234"},
		User{Id: "0002", Name: "Abu", Password: "1234"},
		User{Id: "0003", Name: "naser", Password: "1234"},
		User{Id: "0005", Name: "Hossen", Password: "1234"},
		User{Id: "0006", Name: "samyo", Password: "1234"},
		User{Id: "0007", Name: "Mosharrof", Password: "1234"},
		User{Id: "0008", Name: "Tipu", Password: "1234"},
		User{Id: "0009", Name: "Humayun", Password: "1234"},
		User{Id: "0010", Name: "Jenny", Password: "1234"},
		User{Id: "0011", Name: "Tiham", Password: "1234"},
	}

	i := 0
	for i < len(users) {
		fmt.Println("i is ", i)
		userAsBytes, _ := json.Marshal(users[i])
		APIstub.PutState("USER"+users[i].Id, userAsBytes)
		fmt.Println("Added", users[i])
		i = i + 1
	}

	return shim.Success(nil)
}

func (s *SmartContract) queryUser(APIstub shim.ChaincodeStubInterface, args []string) sc.Response {

	if len(args) != 1 {
		return shim.Error("Incorrect number of arguments. Expecting 1")
	}

	userAsBytes, err := APIstub.GetState(args[0])
	if err != nil {
		return shim.Error("Error while fetching user")
	}
	if len(userAsBytes) == 0 {
		//TODO
		//gotta return this error
		return shim.Error("User doesn't exist!")
	}

	return shim.Success(userAsBytes)
}

func (s *SmartContract) createUser(APIstub shim.ChaincodeStubInterface, args []string) sc.Response {

	if len(args) != 3 {
		return shim.Error("Incorrect number of arguments. Expecting 5")
	}

	// check duplicate registration
	userExists, _ := APIstub.GetState("USER" + args[0])
	if len(userExists) > 0 {
		//TODO
		return shim.Error("User already exists!")
	}

	var user = User{Id: args[0], Name: args[1], Password: args[2]}

	userAsBytes, _ := json.Marshal(user)
	APIstub.PutState("USER"+args[0], userAsBytes)

	return shim.Success(nil)
}

func (s *SmartContract) queryAllUsers(APIstub shim.ChaincodeStubInterface) sc.Response {

	startKey := "USER"
	endKey := "USERz"

	resultsIterator, err := APIstub.GetStateByRange(startKey, endKey)
	if err != nil {
		return shim.Error(err.Error())
	}
	defer resultsIterator.Close()

	// buffer is a JSON array containing QueryResults
	var buffer bytes.Buffer
	buffer.WriteString("[")

	bArrayMemberAlreadyWritten := false
	for resultsIterator.HasNext() {
		queryResponse, err := resultsIterator.Next()
		if err != nil {
			return shim.Error(err.Error())
		}
		// Add a comma before array members, suppress it for the first array member
		if bArrayMemberAlreadyWritten == true {
			buffer.WriteString(",")
		}
		buffer.WriteString("{\"Key\":")
		buffer.WriteString("\"")
		buffer.WriteString(queryResponse.Key)
		buffer.WriteString("\"")

		buffer.WriteString(", \"Record\":")
		// Record is a JSON object, so we write as-is
		buffer.WriteString(string(queryResponse.Value))
		buffer.WriteString("}")
		bArrayMemberAlreadyWritten = true
	}
	buffer.WriteString("]")

	fmt.Printf("- queryAllUsers:\n%s\n", buffer.String())

	return shim.Success(buffer.Bytes())
}

func getSession(APIstub shim.ChaincodeStubInterface) string {
	//gotta check time here
	//string to time
	//layout := "Mon Jan 02 2006 15:04:05 GMT-0700"
	//str := "01 Jan 15 10:00 UTC"
	//t, _ := time.Parse(layout, value)
	//fmt.Println(t.Format(layout))

	timeNow := time.Now()

	startAsBytes, _ := APIstub.GetState("TIME0")
	ln := len(startAsBytes)
	start := string(startAsBytes[1 : ln-1])
	startTime, _ := time.Parse(time.RFC822, start)

	middleAsBytes, _ := APIstub.GetState("TIME1")
	ln = len(middleAsBytes)
	middle := string(middleAsBytes[1 : ln-1])
	middleTime, _ := time.Parse(time.RFC822, middle)

	endAsBytes, _ := APIstub.GetState("TIME2")
	ln = len(endAsBytes)
	end := string(endAsBytes[1 : ln-1])
	endTime, _ := time.Parse(time.RFC822, end)

	fmt.Println(startAsBytes, middleAsBytes, endAsBytes)
	fmt.Println(start, middle, end)
	fmt.Println(startTime, middleTime, endTime, timeNow)

	session := ""

	if timeNow.Before(startTime) {
		session = "before"
	} else if timeNow.After(startTime) && timeNow.Before(middleTime) {
		session = "nomination"
	} else if timeNow.After(middleTime) && timeNow.Before(endTime) {
		session = "vote"
	} else {
		session = "finished"
	}
	return session
}

func (s *SmartContract) whichTime(APIstub shim.ChaincodeStubInterface) sc.Response {

	session := getSession(APIstub)
	return shim.Success([]byte(session))

}

func isNominationValid(APIstub shim.ChaincodeStubInterface, tp string) bool {

	session := getSession(APIstub)
	if tp == "0" && session == "nomination" {
		return true
	} else if tp == "1" && session == "vote" {
		return true
	}
	return false
}

func (s *SmartContract) nominate(APIstub shim.ChaincodeStubInterface, args []string) sc.Response {
	if len(args) != 4 {
		return shim.Error("Incorrect number of arguments. Expecting 5")
	}

	if isNominationValid(APIstub, args[3]) == false {
		//session didn't match
		return shim.Error("Not Allowed to do!")
	}

	var nominationType string
	if args[3] == "0" {
		nominationType = "NOMINATION"
	} else {
		nominationType = "VOTECAST"
	}

	//check if idFrom exists
	voterExists, _ := APIstub.GetState("USER" + args[0])
	if len(voterExists) == 0 {
		return shim.Error("Voter doesn't exist -_-")
	}
	//check if idTo exists
	candidateExists, _ := APIstub.GetState("USER" + args[1])
	if len(candidateExists) == 0 {
		return shim.Error("Candidate doesn't exist -_-")
	}

	//if this is voting
	if args[3] == "1" {
		//TODO: check if candidate was nominated early - in this position
		//...

	} else {
		//one cannot nominate themselves
		if args[0] == args[1] {
			return shim.Error("You cannot nominate yourself!")
		}
	}

	//{IdFrom, Position} duplicate check
	nominationExists, _ := APIstub.GetState(nominationType + args[0] + args[2])
	if len(nominationExists) > 0 {
		return shim.Error("User already voted in this category!")
	}

	var nomination = Nomination{IdFrom: args[0], IdTo: args[1], Position: args[2]}

	nominationAsBytes, _ := json.Marshal(nomination)
	APIstub.PutState(nominationType+args[0]+args[2], nominationAsBytes)

	return shim.Success(nil)
}

func (s *SmartContract) getNominationList(APIstub shim.ChaincodeStubInterface, args []string) sc.Response {

	if len(args) != 1 {
		return shim.Error("Incorrect number of arguments. Expecting 5")
	}

	var opType string
	if args[0] == "0" {
		opType = "NOMINATION"
	} else if args[0] == "1" {
		opType = "VOTECAST"
	} else {
		opType = "POSITION"
	}

	startKey := opType
	endKey := opType + "z"

	resultsIterator, err := APIstub.GetStateByRange(startKey, endKey)
	if err != nil {
		return shim.Error(err.Error())
	}
	defer resultsIterator.Close()

	// buffer is a JSON array containing QueryResults
	var buffer bytes.Buffer
	buffer.WriteString("[")

	bArrayMemberAlreadyWritten := false
	for resultsIterator.HasNext() {
		queryResponse, err := resultsIterator.Next()
		if err != nil {
			return shim.Error(err.Error())
		}
		// Add a comma before array members, suppress it for the first array member
		if bArrayMemberAlreadyWritten == true {
			buffer.WriteString(",")
		}
		buffer.WriteString("{\"Key\":")
		buffer.WriteString("\"")
		buffer.WriteString(queryResponse.Key)
		buffer.WriteString("\"")

		buffer.WriteString(", \"Record\":")
		// Record is a JSON object, so we write as-is
		buffer.WriteString(string(queryResponse.Value))
		buffer.WriteString("}")
		bArrayMemberAlreadyWritten = true
	}
	buffer.WriteString("]")

	fmt.Printf("- queryAllNomination/Votecast/Position:\n%s\n", buffer.String())

	return shim.Success(buffer.Bytes())
}

// The main function is only relevant in unit test mode. Only included here for completeness.
func main() {

	// Create a new Smart Contract
	err := shim.Start(new(SmartContract))
	if err != nil {
		fmt.Printf("Error creating new Smart Contract: %s", err)
	}
}
