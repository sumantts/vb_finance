-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 08, 2025 at 03:36 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ezidesk`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_AdhocPayment` (IN `ResrvId` INT, IN `PayDate` DATE, IN `PayAmount` DECIMAL(5,0), IN `UsrId` SMALLINT)   BEGIN
  DECLARE Cnt INT;

  SET Cnt = ( SELECT
    COUNT(*)
  FROM trans_guest_reservation
  WHERE Reservation_Id = ResrvId);
  IF Cnt>0 THEN
    START TRANSACTION;
INSERT INTO trans_guest_ledger (Reservation_Id, Trans_Date, Narration, Purpose_Cd, Charges_Amount, Collection_Amount, Entered_By, Entered_On)
  VALUES (ResrvId, PayDate, 'Adhoc Payment', 2, NULL, PayAmount, UsrId, NOW());
SELECT
  1 AS RetVal;
    COMMIT;
  ELSE
SELECT
  0 AS RetVal;
END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_AgentDtls` (IN `AgntId` SMALLINT, IN `AgntNm` VARCHAR(100), IN `OrgNm` VARCHAR(150), IN `Addrs` VARCHAR(200), IN `ContNo` VARCHAR(25), IN `PAN` VARCHAR(15), IN `CommP` DECIMAL(5,2), IN `Stat` TINYINT)   BEGIN
  DECLARE Cnt INT;

  SET Cnt=( SELECT
    COUNT(*)
  FROM mst_agent
  WHERE Agent_Id = AgntId);
  IF Cnt=0 THEN
INSERT INTO mst_agent (Agent_Name, Org_Name, Address, Contact_No, PAN_No, Comm_Prcnt, Status)
  VALUES (AgntNm, OrgNm, Addrs, ContNo, PAN, IFNULL(CommP, 0), Stat);
ELSE
UPDATE mst_agent
SET Agent_Name = AgntNm,
    Org_Name = OrgNm,
    Address = Addrs,
    Contact_No = ContNo,
    PAN_No = PAN,
    Comm_Prcnt = IFNULL(CommP, 0),
    Status = Stat
WHERE Agent_Id = AgntId;
END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_CalculateBill_Food` (IN `ResrvNo` INT, IN `AsOnDt` DATE)  READS SQL DATA BEGIN
    DECLARE Done INT DEFAULT FALSE;
 DECLARE Cnt SMALLINT;
    DECLARE OrdId INT;
 DECLARE RmNo VARCHAR(10);
 DECLARE OrdDt DATE;
 DECLARE KotNo INT;
 DECLARE OrdAmt DECIMAL(5,0);
 
    DECLARE RmSrv BIT;
 DECLARE RmSrvAmt DECIMAL(4,0);
 DECLARE SrvChrgs DECIMAL(4,2);

    DECLARE c1 CURSOR FOR
SELECT
  FoodOrd_Id,
  Room_No,
  Order_Date,
  KOT_No,
  IFNULL(Room_Service, 0)
FROM trans_food_ordered
WHERE Reservation_Id = ResrvNo
AND Order_Date <= AsOnDt
ORDER BY Order_Date, FoodOrd_Id;

DECLARE CONTINUE HANDLER FOR NOT FOUND SET Done = TRUE;
    OPEN c1;

getlp: LOOP
    FETCH c1 INTO OrdId,RmNo,OrdDt,KotNo,RmSrv;

    IF Done IS TRUE THEN
      LEAVE getlp;
    END IF;
    
    SET OrdAmt=IFNULL(( SELECT
    SUM(Rate * Quantity)
  FROM trans_food_ordered_detail A,
       mst_menus B
  WHERE A.FoodOrd_Id = OrdId
  AND B.Menu_Id = A.Menu_Id), 0);
    

    SET RmSrvAmt=0;
    IF RmSrv=1 THEN
      SET SrvChrgs=IFNULL(( SELECT
    RmSrv_Chrgs
  FROM mst_miscinfo), 0);
      SET RmSrvAmt=ROUND((OrdAmt*SrvChrgs)/100);
    END IF;

INSERT INTO tmp_billfood (RoomNo, OrdDt, KOT_No, BillAmt, SrvAmt, TotAmount)
  VALUES (RmNo, OrdDt, KotNo, OrdAmt, RmSrvAmt, (OrdAmt + RmSrvAmt));

END LOOP getlp;

    CLOSE c1;
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_CalculateBill_Room` (IN `ResrvNo` INT, IN `AsOnDt` DATE)  READS SQL DATA BEGIN
    DECLARE Done INT DEFAULT FALSE;
 DECLARE Cnt SMALLINT;
    DECLARE RmTyp SMALLINT;
 DECLARE RmNo Varchar(10);
 DECLARE FmDt DATE;
 DECLARE ToDt DATE;
 DECLARE ExtCot TINYINT;
    DECLARE RmRate DECIMAL(5,0);
 DECLARE CotRate DECIMAL(5,0);
 DECLARE ChrgTyp TINYINT;
 DECLARE DayNm VARCHAR(15);
    DECLARE DayNo Tinyint;

    DECLARE c1 CURSOR FOR
SELECT
  (SELECT
      Room_Type
    FROM mst_room_details
    WHERE Room_Id = A.Room_Id),
  (SELECT
      Room_No
    FROM mst_room_details
    WHERE Room_Id = A.Room_Id),
  From_Date,
  IFNULL(Upto_Date, AsOnDt),
  IFNULL(A.Extra_Bed_No, 0)
FROM trans_guest_room A
WHERE Reservation_Id = ResrvNo
ORDER BY Allotment_Id;
DECLARE CONTINUE HANDLER FOR NOT FOUND SET Done = TRUE;
    OPEN c1;

getlp: LOOP
    FETCH c1 INTO RmTyp,RmNo,FmDt,ToDt,ExtCot;

    IF Done IS TRUE THEN
      LEAVE getlp;
    END IF;
    
    SET CotRate=0;
 SET RmRate=0;
 SET ChrgTyp=2;
        
    IF ExtCot>0 THEN
      SET CotRate=IFNULL(( SELECT
    Extra_Bed_Charges
  FROM mst_room_type
  WHERE Room_TId = RmTyp), 0);
      SET ChrgTyp=IFNULL(( SELECT
    Charges_Type_Code
  FROM mst_room_type
  WHERE Room_TId = RmTyp), 2);
END IF;

    SET DayNo=( SELECT
    DATEDIFF(ToDt, FmDt));
    IF DayNo<1 THEN SET DayNo=1;
 END IF;

    SET DayNm= ( SELECT
    DAYNAME(FmDt));
    IF DayNm='Monday' THEN
        SET RmRate=IFNULL(( SELECT
    Rate_Mon
  FROM mst_room_rates_regular
  WHERE Room_TId = RmTyp), 0);
ELSEIF DayNm = 'Tuesday' THEN
        SET RmRate=IFNULL(( SELECT
    Rate_Tue
  FROM mst_room_rates_regular
  WHERE Room_TId = RmTyp), 0);
ELSEIF DayNm = 'Wednesday' THEN
        SET RmRate=IFNULL(( SELECT
    Rate_Wed
  FROM mst_room_rates_regular
  WHERE Room_TId = RmTyp), 0);
ELSEIF DayNm = 'Thursday' THEN
        SET RmRate=IFNULL(( SELECT
    Rate_Thu
  FROM mst_room_rates_regular
  WHERE Room_TId = RmTyp), 0);
ELSEIF DayNm = 'Friday' THEN
        SET RmRate=IFNULL(( SELECT
    Rate_Fri
  FROM mst_room_rates_regular
  WHERE Room_TId = RmTyp), 0);
ELSEIF DayNm = 'Saturday' THEN
        SET RmRate=IFNULL(( SELECT
    Rate_Sat
  FROM mst_room_rates_regular
  WHERE Room_TId = RmTyp), 0);
ELSE
 
        SET RmRate=IFNULL(( SELECT
    Rate_Sun
  FROM mst_room_rates_regular
  WHERE Room_TId = RmTyp), 0);
END IF;

    SET Cnt=( SELECT
    COUNT(*)
  FROM mst_room_rates_special
  WHERE FmDt BETWEEN Date_From AND Date_Upto
  AND Room_TId = RmTyp
  AND Status = 1);
    IF Cnt>0 Then
        SET RmRate=IFNULL(( SELECT
    Rate_Spl
  FROM mst_room_rates_special
  WHERE FmDt BETWEEN Date_From AND Date_Upto
  AND Room_TId = RmTyp
  AND Status = 1), 0);
END IF;

INSERT INTO tmp_billroom (RoomNo, FrmDt, ToDate, DaysNo, RmRate, RmAmt, ExtCotAmt, TotAmount)
  VALUES (RmNo, FmDt, ToDt, DayNo, RmRate, (RmRate * DayNo), ((CotRate * ExtCot) * DayNo), ROUND((RmRate * DayNo) + ((CotRate * ExtCot) * DayNo), 0));

END LOOP getlp;

    CLOSE c1;
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_CalculateBill_Service` (IN `ResrvNo` INT, IN `AsOnDt` DATE)  READS SQL DATA BEGIN
    DECLARE Done INT DEFAULT FALSE;
 
    DECLARE SrvId INT;
 DECLARE OrdDt DATE;
 DECLARE SrvAmt DECIMAL(5,0);
 DECLARE Qty TINYINT;
 DECLARE OrdAmt DECIMAL(5,0);

    DECLARE c1 CURSOR FOR
SELECT
  Order_Date,
  Service_Id,
  Charges_Amt,
  Quantity,
  Payable_Amt
FROM trans_service_ordered
WHERE Reservation_Id = ResrvNo
AND Order_Date <= AsOnDt
ORDER BY Order_Date, ServOrd_Id;

DECLARE CONTINUE HANDLER FOR NOT FOUND SET Done = TRUE;
    OPEN c1;

getlp: LOOP
    FETCH c1 INTO OrdDt,SrvId,SrvAmt,Qty,OrdAmt;

    IF Done IS TRUE THEN
      LEAVE getlp;
    END IF;

INSERT INTO tmp_billservice (SrvDate, ServiceId, SrvAmount, SrvQty, TotAmount)
  VALUES (OrdDt, SrvId, SrvAmt, Qty, OrdAmt);

END LOOP getlp;

    CLOSE c1;
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_Calculate_Rent` ()  READS SQL DATA BEGIN
  
  DECLARE Done INT DEFAULT FALSE;
  DECLARE ChrgAmt DECIMAL(5,0);
 DECLARE RmTyp SMALLINT;
 DECLARE DayNm VARCHAR(15);
 DECLARE RmRate DECIMAL(5,0);
  DECLARE Sl Tinyint;
 DECLARE BkDt DATE;
 DECLARE RmNo Varchar(10);
 DECLARE ExCot TINYINT;
 DECLARE DayNo Tinyint;
  DECLARE Cnt SMALLINT;
 DECLARE BdChrg DECIMAL(6,2);
 DECLARE ChrgTyp TINYINT;

    DECLARE c1 CURSOR FOR
SELECT
  SlNo,
  Book_Date,
  Room_No,
  IFNULL(Extra_Bed, 0),
  Days_No
FROM tmp_room
ORDER BY SlNo;
DECLARE CONTINUE HANDLER FOR NOT FOUND SET Done = TRUE;
    OPEN c1;

getlp: LOOP
    FETCH c1 INTO Sl,BkDt,RmNo,ExCot,DayNo;

    IF Done IS TRUE THEN
      LEAVE getlp;
    END IF;
    
    SET BdChrg=0;
 SET ChrgTyp=2;
    SET RmTyp=( SELECT
    Room_Type
  FROM mst_room_details
  WHERE Room_No = RmNo);
    
    IF ExCot>0 THEN
      SET BdChrg=IFNULL(( SELECT
    Extra_Bed_Charges
  FROM mst_room_type
  WHERE Room_TId = RmTyp), 0);
      SET ChrgTyp=IFNULL(( SELECT
    Charges_Type_Code
  FROM mst_room_type
  WHERE Room_TId = RmTyp), 2);
END IF;

    SET DayNm= ( SELECT
    DAYNAME(BkDt));
    IF DayNm='Monday' THEN
        SET RmRate=IFNULL(( SELECT
    Rate_Mon
  FROM mst_room_rates_regular
  WHERE Room_TId = RmTyp), 0);
ELSEIF DayNm = 'Tuesday' THEN
        SET RmRate=IFNULL(( SELECT
    Rate_Tue
  FROM mst_room_rates_regular
  WHERE Room_TId = RmTyp), 0);
ELSEIF DayNm = 'Wednesday' THEN
        SET RmRate=IFNULL(( SELECT
    Rate_Wed
  FROM mst_room_rates_regular
  WHERE Room_TId = RmTyp), 0);
ELSEIF DayNm = 'Thursday' THEN
        SET RmRate=IFNULL(( SELECT
    Rate_Thu
  FROM mst_room_rates_regular
  WHERE Room_TId = RmTyp), 0);
ELSEIF DayNm = 'Friday' THEN
        SET RmRate=IFNULL(( SELECT
    Rate_Fri
  FROM mst_room_rates_regular
  WHERE Room_TId = RmTyp), 0);
ELSEIF DayNm = 'Saturday' THEN
        SET RmRate=IFNULL(( SELECT
    Rate_Sat
  FROM mst_room_rates_regular
  WHERE Room_TId = RmTyp), 0);
ELSE
 
        SET RmRate=IFNULL(( SELECT
    Rate_Sun
  FROM mst_room_rates_regular
  WHERE Room_TId = RmTyp), 0);
END IF;

    SET Cnt=( SELECT
    COUNT(*)
  FROM mst_room_rates_special
  WHERE BkDt BETWEEN Date_From AND Date_Upto
  AND Room_TId = RmTyp
  AND Status = 1);
    IF Cnt>0 Then
        SET RmRate=IFNULL(( SELECT
    Rate_Spl
  FROM mst_room_rates_special
  WHERE BkDt BETWEEN Date_From AND Date_Upto
  AND Room_TId = RmTyp
  AND Status = 1), 0);
END IF;

    IF BdChrg>0 AND ChrgTyp=1 THEN
      SET BdChrg=ROUND(RmRate*BdChrg,0);
    END IF;
    
    SET ChrgAmt=(RmRate*DayNo) + ((BdChrg * ExCot) * DayNo);

UPDATE tmp_room
SET Chrg_Amt = ChrgAmt
WHERE SlNo = Sl;

END LOOP getlp;

    CLOSE c1;

SELECT
  SUM(Chrg_Amt) AS Rent
FROM tmp_room;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_CheckOut` (IN `ResrvNo` INT, IN `ChkOutDt` DATE, IN `DiscAmt` DECIMAL(5,2), IN `CurAmtPaid` DECIMAL(8,2), IN `UsrId` SMALLINT)   BEGIN
    DECLARE RsvId INT;
 DECLARE RtnVal BIT;
 DECLARE FnYr VARCHAR(10);
 DECLARE InvSt VARCHAR(15);
 DECLARE InvNo SMALLINT;
    DECLARE BillAmt DECIMAL(10,2);
 DECLARE GSTAmt DECIMAL(6,2);
 DECLARE TotAmt DECIMAL(10,2);
 DECLARE ChkInDt DATE;
 DECLARE Cnt INT;
    DECLARE ROff DECIMAL(4,2);
 DECLARE NetAmt DECIMAL(10,2);
 DECLARE AmtPaid DECIMAL(10,2);
 DECLARE AmtDue DECIMAL(10,2);
    DECLARE ChkOutId Int;
    
    SET RtnVal=0;

    SET FnYr=( SELECT
    Financial_Year
  FROM app_settings);
    SET InvNo=( SELECT
    Invoice_No + 1
  FROM app_settings);
    
    IF InvNo<10 THEN
       SET InvSt=CONCAT('0000',InvNo);
    ELSEIF InvNo<100 THEN
      SET InvSt=CONCAT('000',InvNo);
    ELSEIF InvNo<1000 THEN
      SET InvSt=CONCAT('00',InvNo);
    ELSEIF InvNo<10000 THEN
      SET InvSt=CONCAT('0',InvNo);
    ELSE
      SET InvSt=InvNo;
    END IF;
    
    SET InvSt=CONCAT(InvSt,'/',FnYr);

    SET RsvId=IFNULL(( SELECT
    Reservation_Id
  FROM trans_guest_reservation
  WHERE Reservation_No = ResrvNo
  AND Status = 1), 0);
    IF RsvId>0 THEN
      START TRANSACTION;
      
      SET ChkInDt=( SELECT
    CheckIn_Date
  FROM trans_guest_reservation
  WHERE Reservation_No = ResrvNo);
  
      
      SET BillAmt=IFNULL(( SELECT
    Bill_Amt
  FROM tmp_bill), 0);
      SET GSTAmt=IFNULL(( SELECT
    Gst_Amt
  FROM tmp_bill), 0);
      SET TotAmt=IFNULL(( SELECT
    Tot_Amount
  FROM tmp_bill), 0);
      SET ROff=IFNULL(( SELECT
    Roff_Amt
  FROM tmp_bill), 0);
      SET NetAmt=IFNULL(( SELECT
    Net_Amt
  FROM tmp_bill), 0);
      SET AmtPaid=IFNULL(( SELECT
    Amt_Paid
  FROM tmp_bill), 0);
      SET AmtDue=IFNULL(( SELECT
    Amt_Due
  FROM tmp_bill), 0);

INSERT INTO trans_chekout_ledger (Reservation_Id, Invoice_No, CheckIn_Date, Checkout_Date, Bill_Amount, Gst_Amount,
Total_Amount, Round_Off, Net_Amount, Disc_Amount, Amt_Paid, Coll_Amount, Due_Amount, Remarks, Entered_By, Entered_On)
  VALUES (RsvId, InvSt, ChkInDt, ChkOutDt, BillAmt, GSTAmt, TotAmt, ROff, NetAmt, DiscAmt, AmtPaid, CurAmtPaid, (NetAmt - DiscAmt - AmtPaid - CurAmtPaid), '', UsrId, NOW());
  

      SET ChkOutId=( SELECT
    LAST_INSERT_ID());
INSERT INTO trans_checkout_details (Checkout_Id, Room_Bill, Food_Bill, Service_Bill)
  VALUES (ChkOutId, IFNULL((SELECT SUM(TotAmount) FROM tmp_billroom), 0), IFNULL((SELECT SUM(TotAmount) FROM tmp_billfood), 0), IFNULL((SELECT SUM(TotAmount) FROM tmp_billservice), 0));
UPDATE trans_checkout_details
SET Total_Bill = (Room_Bill + Food_Bill + Service_Bill);

INSERT INTO trans_guest_ledger (Reservation_Id, Trans_Date, Narration, Purpose_Cd, Charges_Amount, Collection_Amount, Entered_By, Entered_On)
  VALUES (RsvId, ChkOutDt, 'Checkout Payment', 3, AmtDue, CurAmtPaid, UsrId, NOW());

UPDATE trans_guest_room
SET Upto_Date = ChkOutDt
WHERE Reservation_Id = RsvId
AND Upto_Date IS NULL;
UPDATE trans_guest_reservation
SET Status = 2
WHERE Reservation_Id = RsvId;
UPDATE app_settings
SET Invoice_No = InvNo;

      SET RtnVal=1;

      -- ROLLBACK;
    END IF;

SELECT
  RtnVal AS RtnStat;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_ClearData` ()   BEGIN
  TRUNCATE TABLE tmp_food;
  TRUNCATE TABLE tmp_room;
  TRUNCATE TABLE tmp_billroom;
  TRUNCATE TABLE tmp_billfood;
  TRUNCATE TABLE tmp_billservice;
  TRUNCATE TABLE tmp_bill;

  TRUNCATE TABLE trans_agent_ledger;
  TRUNCATE TABLE trans_chekout_ledger;
  TRUNCATE TABLE trans_food_ordered;
  TRUNCATE TABLE trans_food_ordered_detail;
  TRUNCATE TABLE trans_guest_booking;
  TRUNCATE TABLE trans_guest_ledger;
  TRUNCATE TABLE trans_guest_reservation;
  TRUNCATE TABLE trans_guest_room;
  TRUNCATE TABLE trans_service_ordered;
  TRUNCATE TABLE trans_expenses;

UPDATE mst_room_details
SET STATUS = 1
WHERE ROOM_ID <= 7;
UPDATE app_settings
SET Invoice_No = 0;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_ExpVoucher` (`ExpDate` DATE, `PurpCd` SMALLINT, `VendName` VARCHAR(75), `Particls` LONGTEXT, `BillAmt` DECIMAL(10,2), `PaidAmt` DECIMAL(10,2), `UsrId` SMALLINT, `ExpId` INT)   BEGIN
  DECLARE Id INT;
  IF IFNULL(ExpId,0)=0 THEN
INSERT INTO trans_expenses (Exp_Date, Purpose_Cd, Vendor_Name, Particulars, Bill_Amount, Paid_Amount, Due_Amount, Entered_By, Entered_On)
  VALUES (ExpDate, PurpCd, VendName, Particls, IFNULL(BillAmt, 0), IFNULL(PaidAmt, 0), (IFNULL(BillAmt, 0) - IFNULL(PaidAmt, 0)), UsrId, NOW());
    SET Id=( SELECT
    LAST_INSERT_ID());
ELSE
UPDATE trans_expenses
SET Purpose_Cd = PurpCd,
    Vendor_Name = VendName,
    Particulars = Particls,
    Bill_Amount = IFNULL(BillAmt, 0),
    Paid_Amount = IFNULL(PaidAmt, 0),
    Due_Amount = (IFNULL(BillAmt, 0) - IFNULL(PaidAmt, 0)),
    Entered_By = UsrId,
    Entered_On = NOW()
WHERE Exp_Id = IFNULL(ExpId, 0);
     SET Id=ExpId;
  END IF;
SELECT
  Id AS EId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_GenerateInvoice` (IN `ResrvNo` INT, IN `AsOnDt` DATE)   BEGIN
    
    DECLARE RentAmt DECIMAL(10,2);
 DECLARE FoodAmt DECIMAL(10,2);
 DECLARE ServiceAmt DECIMAL(10,2);
    DECLARE RentGSTPrcnt DECIMAL(4,2);
 DECLARE FoodGSTPrcnt DECIMAL(4,2);
 DECLARE SrvGSTPrcnt DECIMAL(4,2);
    DECLARE RentGSTAmt DECIMAL(8,2);
 DECLARE FoodGSTAmt DECIMAL(8,2);
 DECLARE SrvGSTAmt DECIMAL(8,2);
    DECLARE TotGSTAmt DECIMAL(8,2);
 DECLARE TotBillAmt DECIMAL(10,2);
 DECLARE GrandTotal DECIMAL(10,2);
 
    DECLARE ROff DECIMAL(3,2);
 DECLARE AmtPaid DECIMAL(10,2);
 DECLARE AmtDue DECIMAL(10,2);
 DECLARE ResrvId INT;

    START TRANSACTION;

    TRUNCATE TABLE tmp_billroom;
    TRUNCATE TABLE tmp_billfood;
    TRUNCATE TABLE tmp_billservice;
    TRUNCATE TABLE tmp_bill;

    SET ResrvId=IFNULL(( SELECT
    Reservation_Id
  FROM trans_guest_reservation
  WHERE Reservation_No = ResrvNo), 0);

    IF ResrvId>0 THEN

-- UPDATE trans_guest_room SET Upto_Date=AsOnDt WHERE Reservation_Id=ResrvId AND Upto_Date IS NULL;
CALL usp_CalculateBill_Room(ResrvId, AsOnDt);

CALL usp_CalculateBill_Food(ResrvId, AsOnDt);

CALL usp_CalculateBill_Service(ResrvId, AsOnDt);
      
      SET RentAmt=IFNULL(( SELECT
    SUM(TotAmount)
  FROM tmp_billroom), 0);
      SET FoodAmt=IFNULL(( SELECT
    SUM(TotAmount)
  FROM tmp_billfood), 0);
      SET ServiceAmt=IFNULL(( SELECT
    SUM(TotAmount)
  FROM tmp_billservice), 0);
    
  
      SET RentGSTPrcnt = IFNULL(( SELECT
    RmRent_GST
  FROM mst_miscinfo), 0);
      SET FoodGSTPrcnt = IFNULL(( SELECT
    FoodBill_GST
  FROM mst_miscinfo), 0);
      SET SrvGSTPrcnt = IFNULL(( SELECT
    OthrSrv_GST
  FROM mst_miscinfo), 0);
      
      SET RentGSTAmt = ROUND((RentAmt*RentGSTPrcnt)/100,2);
      SET FoodGSTAmt = ROUND((FoodAmt*FoodGSTPrcnt)/100,2);
      SET SrvGSTAmt = ROUND((ServiceAmt*SrvGSTPrcnt)/100,2);
  
      SET TotBillAmt= ROUND((RentAmt+FoodAmt+ServiceAmt),2);
      SET TotGSTAmt = ROUND((RentGSTAmt+FoodGSTAmt+SrvGSTAmt),2);
      SET GrandTotal = ROUND((TotBillAmt+TotGSTAmt),2);
  
      SET ROff=0;
      IF ROUND(GrandTotal,0)>GrandTotal THEN
        SET ROff=ROUND(GrandTotal,0)-GrandTotal;
        SET GrandTotal=ROUND(GrandTotal,0);
  
      ELSE
        SET ROff=GrandTotal-ROUND(GrandTotal,0);
        SET GrandTotal=ROUND(GrandTotal,0);
  
      END IF;
      SET GrandTotal=ROUND(GrandTotal,0);

      SET AmtPaid=0;
      SET AmtPaid=IFNULL(( SELECT
    SUM(IFNULL(Collection_Amount, 0))
  FROM trans_guest_ledger
  WHERE Reservation_Id = ResrvId), 0);
      SET AmtDue = GrandTotal - AmtPaid;
    END IF;

    COMMIT;

INSERT INTO tmp_bill (Bill_Amt, Gst_Amt, Tot_Amount, Roff_Amt, Net_Amt, Amt_Paid, Amt_Due)
  VALUES (TotBillAmt, TotGSTAmt, (TotBillAmt + TotGSTAmt), ROff, GrandTotal, AmtPaid, AmtDue);

SELECT
  RentAmt AS RoomRent,
  FoodAmt AS FoodBill,
  ServiceAmt AS Services,
  TotBillAmt AS BillTotal,
  TotGSTAmt AS GST,
  ROff AS RoundOff,
  GrandTotal AS TotalBillAmount,
  AmtPaid AS AmountPaid,
  AmtDue AS NetDue;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_GetAdvanceAmt` (IN `BookNo` INT)  READS SQL DATA BEGIN
  DECLARE AdvAmt DECIMAL(5,0);

  SET AdvAmt=IFNULL(( SELECT
    IFNULL(Advance_Amt, 0)
  FROM trans_guest_booking
  WHERE Booking_No = BookNo), 0);
SELECT
  AdvAmt AS Adv;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_GetAgent` ()  READS SQL DATA BEGIN
SELECT
  Agent_Id AS Id,
  Agent_Name AS AgntNm
FROM mst_agent
WHERE Status = 1
ORDER BY Agent_Name;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_GetAgentDtls` (IN `AgntId` SMALLINT)  READS SQL DATA BEGIN
SELECT
  Agent_Id AS Id,
  Agent_Name AS AgntNm,
  Org_Name AS OrgNm,
  Address AS Adds,
  Contact_No AS ContNo,
  PAN_No AS PAN,
  IFNULL(Comm_Prcnt, 0) AS CommP,
  Status AS Stat
FROM mst_agent
WHERE Agent_Id = AgntId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_GetExpDtls` (IN `ExpId` INT)  READS SQL DATA BEGIN
SELECT
  DATE_FORMAT(Exp_Date, '%d-%m-%Y') AS ExpDt,
  Purpose_Cd AS PurpCd,
  (SELECT
      Type_Name
    FROM mst_exp_type
    WHERE Exp_TId = A.Purpose_Cd) AS PurpNm,
  Vendor_Name AS VendNm,
  Particulars AS Partcls,
  Bill_Amount AS BAmt,
  Paid_Amount AS PAmt
FROM trans_expenses A
WHERE Exp_Id = ExpId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_GetGuestRoomNo` (IN `ResrvId` INT)  READS SQL DATA BEGIN
SELECT
  B.Room_Id,
  B.Room_No
FROM trans_guest_room A,
     mst_room_details B
WHERE A.Reservation_Id = ResrvId
AND B.Room_Id = A.Room_Id
AND B.Status = 2
ORDER BY B.Room_Id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_GetMenuDtls` (IN `MnuCd` SMALLINT)  READS SQL DATA BEGIN
SELECT
  Category_Id AS CatId,
  Menu_Code AS MnuCd,
  Menu_Name AS MnNm,
  Menu_Desc AS MnuDesc,
  Rate AS MnuRt,
  Status AS Stat
FROM mst_menus
WHERE Menu_Code = MnuCd;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_GetMenus` (IN `MenuCd` SMALLINT)  READS SQL DATA BEGIN
  IF MenuCd>0 THEN
SELECT
  Menu_Id,
  Menu_Name
FROM mst_menus
WHERE Menu_Code = MenuCd
AND Status = 1;
ELSE
SELECT
  Menu_Id,
  Menu_Name
FROM mst_menus
WHERE Status = 1
ORDER BY Menu_Name;
END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_GetMnuCatg` ()  READS SQL DATA BEGIN
SELECT
  Category_Id AS Id,
  Categ_Name AS CtgNm
FROM mst_menu_category
ORDER BY Category_Id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_GetOptionList` (IN `OptGrpId` SMALLINT)  READS SQL DATA BEGIN
SELECT
  Opt_Code AS OptId,
  Opt_Description AS OptName
FROM appl_options
WHERE Opt_Grp_Id = OptGrpId
ORDER BY Srl_No;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_GetRmPrice` (IN `RTypeId` SMALLINT)  READS SQL DATA BEGIN
SELECT
  Rate_Mon AS MonD,
  Rate_Tue AS TueD,
  Rate_Wed AS WedD,
  Rate_Thu AS ThuD,
  Rate_Fri AS FriD,
  Rate_Sat AS SatD,
  Rate_Sun AS SunD
FROM mst_room_rates_regular
WHERE Room_TId = RTypeId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_GetRoomNoAvailable` (IN `RmTyp` SMALLINT)  READS SQL DATA BEGIN
  IF RmTyp>0 THEN
SELECT
  Room_Id AS Id,
  Room_No AS RmNo
FROM mst_room_details
WHERE Room_Type = RmTyp
AND Booking_Allowed = 1
AND Status = 1
ORDER BY Room_Id;
ELSE
SELECT
  Room_Id AS Id,
  Room_No AS RmNo
FROM mst_room_details
WHERE Booking_Allowed = 1
AND Status = 1
ORDER BY Room_Id;
END IF;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_GetRoomType` ()  READS SQL DATA BEGIN
SELECT
  Room_TId AS OptId,
  Room_TName AS OptName
FROM mst_room_type
WHERE Status = 1
ORDER BY Room_TId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_GetService` ()  READS SQL DATA BEGIN
SELECT
  Service_Id AS Id,
  Service_Name AS SrvNm
FROM mst_paid_services
WHERE Status = 1
ORDER BY Id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_GetServiceChrgs` (IN `ServId` SMALLINT)  READS SQL DATA BEGIN
SELECT
  IFNULL(Serv_Charges, 0) AS SrvChrgs
FROM mst_paid_services
WHERE Service_Id = ServId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_GetServiceDtls` (IN `SrviceId` SMALLINT)  READS SQL DATA BEGIN
SELECT
  Service_Id AS Id,
  Service_Name AS SrvNm,
  Serv_Desc AS SrvDesc,
  IFNULL(Serv_Charges, 0) AS Chrgs,
  IFNULL(Gst_Prcnt, 0) AS GST
FROM mst_paid_services
WHERE Service_Id = SrviceId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_GetServiceOrdered` (IN `ResrvId` INT)  READS SQL DATA BEGIN
SELECT
  DATE_FORMAT(Order_Date, '%d-%m-%Y') AS SchDt,
  A.Service_Id,
  B.Service_Name AS SrvNm,
  IFNULL(Charges_Amt, 0) SrvAmt,
  Quantity AS Qty,
  IFNULL(Payable_Amt, 0) AS PayablAmt,
  Remarks AS Rmks,
  (SELECT
      Opt_Description
    FROM appl_options
    WHERE Opt_Grp_Id = 8
    AND Opt_Code = A.Order_Status) AS Stats
FROM trans_service_ordered A,
     mst_paid_services B
WHERE Reservation_Id = ResrvId
AND B.Service_Id = A.Service_Id
ORDER BY Order_Date DESC, ServOrd_Id DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_GetSplPrice` (IN `SplId` SMALLINT)  READS SQL DATA BEGIN
SELECT
  Event_Name AS EvntNm,
  Date_From AS FrmDt,
  Date_Upto AS ToDt,
  Rate_Spl AS SplRt,
  Status AS Sts
FROM mst_room_rates_special
WHERE Rate_Id_Spl = SplId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_GetStaff` ()  READS SQL DATA BEGIN
SELECT
  Staff_Id AS Id,
  Nick_Name AS StfNm
FROM mst_staff
WHERE Status = 1
AND Remarks = 'Service'
ORDER BY Nick_Name;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_GetStaffDtls` (IN `StfId` SMALLINT)  READS SQL DATA BEGIN
SELECT
  Staff_Id AS Id,
  Staff_Name AS StfNm,
  Nick_Name AS NckNm,
  Guardian_Name AS GurdNm,
  Address AS Adrs,
  Cont_No_Primary AS ContactP,
  Cont_No_Second AS ContactS,
  Aadhar_No AS UIDNo,
  CASE Gender_Cd WHEN 1 THEN 1 ELSE 0 END AS Gndr,
  Age AS StfAge,
  Join_Date AS JnDate,
  IFNULL(Salary, 0) AS Wages,
  Designation AS Desig,
  Bank_Dtls AS BankAc,
  Remarks AS Rmks,
  Status AS StfStat,
  Release_Date AS ResigDt
FROM mst_staff
WHERE Staff_Id = StfId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_GetUsrDtls` (IN `UsrID` SMALLINT)  READS SQL DATA BEGIN
SELECT
  User_Id AS Id,
  UGrp_Id AS GrpId,
  UFull_Name AS FullNm,
  Usr_Name AS UsrNm
FROM appl_users
WHERE User_Id = UsrID;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_GetUsrGrp` ()  READS SQL DATA BEGIN
SELECT
  UGrp_Id AS Id,
  UGrp_Name AS GrpNm
FROM appl_user_group
WHERE Is_Active = 1
ORDER BY UGrp_Name;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_GuestBooking` (IN `BookNo` INT, `FstNm` VARCHAR(75), `LstNm` VARCHAR(75), `ContNo` VARCHAR(15), IN `RTypId` SMALLINT, `NoOfRm` TINYINT, `ChkInDt` DATE, `StayDur` TINYINT, `Adlt` TINYINT, `Chld` TINYINT, `AgntID` SMALLINT, IN `SrvID` SMALLINT, IN `AdvDt` DATE, IN `AdvAmt` DECIMAL(5,0), IN `Remks` VARCHAR(255), IN `UsrId` SMALLINT)   BEGIN
  DECLARE Cnt INT;
 DECLARE BkId INT;
 DECLARE BkNo INT;
 DECLARE CkhOutDt DATE;

  SET Cnt = ( SELECT
    COUNT(*)
  FROM trans_guest_booking
  WHERE Booking_No = BookNo);

  IF Cnt=0 THEN
    START TRANSACTION;
 
      SET BkId=( SELECT
    IFNULL(MAX(Booking_Id), 0) + 1
  FROM trans_guest_booking);
      
      SET BkNo=( SELECT
    CONCAT(MID(YEAR(NOW()), 3, 2), CASE LENGTH(MONTH(NOW())) WHEN 1 THEN '0' ELSE '' END, MONTH(NOW()),
    CASE LENGTH(DAY(NOW())) WHEN 1 THEN '0' ELSE '' END, DAY(NOW())));
      SET Cnt = ( SELECT
    COUNT(*)
  FROM trans_guest_booking
  WHERE MID(Booking_No, 1, 6) = BkNo);
      SET Cnt = Cnt+ 1;
      IF Cnt<10 THEN
        SET BkNo = ( SELECT
    CONCAT(BkNo, '0', Cnt));
ELSE
        SET BkNo = ( SELECT
    CONCAT(BkNo, Cnt));
END IF;

      SET CkhOutDt=DATE_ADD(ChkInDt, INTERVAL StayDur DAY);

INSERT INTO trans_guest_booking (Booking_Id, Booking_No, First_Name, Last_Name, Contact_No, Room_TId,
NoOf_Room, CheckIn_Date, Stay_Duration, ExpChkOut_Dt, Adult, Child, Agent_Id, Serv_Id, Adv_Date, Advance_Amt,
Note, Status, Entered_By, Entered_On)
  VALUES (BkId, BkNo, FstNm, LstNm, ContNo, RTypId, NoOfRm, ChkInDt, StayDur, CkhOutDt, Adlt, Chld, IFNULL(AgntID, 0), IFNULL(SrvID, 0), IFNULL(AdvDt, NULL), IFNULL(AdvAmt, 0), Remks, CASE IFNULL(AdvAmt, 0) WHEN 0 THEN 1 ELSE 2 END, UsrId, NOW());
      
      IF AdvAmt>0 Then
INSERT INTO trans_guest_ledger (Reservation_Id, Trans_Date, Narration, Purpose_Cd, Charges_Amount, Collection_Amount, Entered_By, Entered_On)
  VALUES (NULL, AdvDt, CONCAT('Booking Advance - ', BkNo), 1, NULL, AdvAmt, UserId, NOW());
END IF;

SELECT
  BkNo AS BookNo;
    COMMIT;

  ELSE
      SET CkhOutDt=DATE_ADD(ChkInDt, INTERVAL StayDur DAY);
UPDATE trans_guest_booking
SET First_Name = FstNm,
    Last_Name = LstNm,
    Contact_No = ContNo,
    Room_TId = RTypId,
    NoOf_Room = NoOfRm,
    CheckIn_Date = ChkInDt,
    Stay_Duration = StayDur,
    ExpChkOut_Dt = CkhOutDt,
    Adult = Adlt,
    Child = Chld,
    Agent_Id = IFNULL(AgntID, 0),
    Serv_Id = IFNULL(SrvID, 0),
    Adv_Date = IFNULL(AdvDt, NULL),
    Advance_Amt = IFNULL(AdvAmt, 0),
    Note = Remks,
    Status = CASE IFNULL(AdvAmt, 0) WHEN O THEN 1 ELSE 2 END,
    Entered_By = UsrId,
    Entered_On = NOW()
WHERE Booking_No = BookNo;

SELECT
  BkNo AS BookNo;
END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_GuestFoodOrder` (IN `OrderDt` DATE, IN `OrderTm` VARCHAR(15), IN `ResrvId` INT, IN `RoomNo` VARCHAR(10), IN `RoomSrv` BIT, IN `SplInst` VARCHAR(100), IN `DelBoy` VARCHAR(75), IN `UserId` SMALLINT)   BEGIN
  DECLARE Cnt INT;
 DECLARE KoTNo INT;
 DECLARE OrdId INT;

  SET KoTNo=( SELECT
    DATE_FORMAT(NOW(), '%y'));
  SET Cnt = ( SELECT
    COUNT(*)
  FROM trans_food_ordered
  WHERE YEAR(Order_Date) = YEAR(NOW()));
  SET Cnt = Cnt+ 1;
  
  SET KoTNo = ( SELECT
    CONCAT(KoTNo, Cnt));
  
  START TRANSACTION;
INSERT INTO trans_food_ordered (Order_Date, Order_Time, KOT_No, Reservation_Id, Room_Service, Room_No, Special_Ins, Order_Status,
Delivered_By, Entered_By, Entered_On)
  VALUES (OrderDt, OrderTm, KoTNo, ResrvId, RoomSrv, RoomNo, SplInst, 1, DelBoy, UserId, NOW());

    SET OrdId=( SELECT
    LAST_INSERT_ID());

INSERT INTO trans_food_ordered_detail (FoodOrd_Id, Menu_Id, Quantity)
  SELECT
    OrdId,
    Memu_Id,
    Quantity
  FROM tmp_food
  ORDER BY SlNo;

  COMMIT;
SELECT
  KoTNo AS KoT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_GuestReservation` (IN `BookNo` INT, `FstNm` VARCHAR(75), `LstNm` VARCHAR(75), `ContNo` VARCHAR(15), IN `UIDNo` VARCHAR(25), IN `Addr1` VARCHAR(150), IN `Addr2` VARCHAR(150), IN `ChkInDt` DATE, IN `DayNo` TINYINT, IN `ChkOutDt` DATE, IN `AdltNo` TINYINT, IN `ChldNo` TINYINT, IN `NoOfRm` TINYINT, IN `AdvAmt` DECIMAL(5,0), IN `CurPayAmt` DECIMAL(5,0), IN `AgntId` SMALLINT, IN `SplRqst` VARCHAR(200), IN `UserId` SMALLINT)   BEGIN
  DECLARE Cnt INT;
 DECLARE BkId INT;
 DECLARE ResrvId INT;
 DECLARE ResrvNo INT;
 DECLARE RmRent DECIMAL(5,0);
 DECLARE AdvDt DATE;

  SET BkId = IFNULL(( SELECT
    Booking_Id
  FROM trans_guest_booking
  WHERE Booking_No = BookNo), 0);
  SET ResrvId = ( SELECT
    IFNULL(MAX(Reservation_Id), 0) + 1
  FROM trans_guest_reservation);

  SET ResrvNo=( SELECT
    YEAR(ChkInDt));
  SET Cnt = ( SELECT
    COUNT(*)
  FROM trans_guest_reservation
  WHERE MID(Reservation_No, 1, 4) = ResrvNo);
  SET Cnt = Cnt+ 1;

  IF Cnt>100 THEN
     SET ResrvNo = ( SELECT
    CONCAT(ResrvNo, '0', Cnt));
ELSEIF Cnt > 10 THEN
     SET ResrvNo = ( SELECT
    CONCAT(ResrvNo, '00', Cnt));
ELSE
     SET ResrvNo = ( SELECT
    CONCAT(ResrvNo, '000', Cnt));
END IF;

  SET RmRent=IFNULL(( SELECT
    SUM(Chrg_Amt)
  FROM tmp_room), 0);

  START TRANSACTION;
INSERT INTO trans_guest_reservation (Reservation_Id, Booking_Id, Reservation_No, First_Name, Last_Name, Contact_No,
Aadhar_No, Address_1, Address_2, CheckIn_Date, Days_No, CheckOut_Date, Guest_No, Adult_No, Child_No, NoOf_Room,
Room_Rent, Advance_Amount, Agent_Id, Special_Request, Status, Entered_By, Entered_On)
  VALUES (ResrvId, CASE BkId WHEN 0 THEN NULL ELSE BkId END, ResrvNo, FstNm, LstNm, ContNo, UIDNo, Addr1, Addr2, ChkInDt, DayNo, ChkOutDt, (AdltNo + ChldNo), AdltNo, ChldNo, NoOfRm, RmRent, (AdvAmt + CurPayAmt), CASE AgntId WHEN 0 THEN NULL ELSE AgntId END, SplRqst, 1, UserId, NOW());
    
    IF BkId>0 THEN
UPDATE trans_guest_booking
SET Status = 4
WHERE Booking_Id = BkId;
END IF;

--     IF BookNo>0 Then
--        SET AdvAmt=IFNULL(( SELECT IFNULL(Advance_Amt, 0) FROM trans_guest_booking WHERE Booking_No = BookNo), 0);
--        IF AdvAmt>0 Then
--           SET AdvDt=( SELECT Adv_Date FROM trans_guest_booking WHERE Booking_No = BookNo);
--           INSERT INTO trans_guest_ledger (Reservation_Id, Trans_Date, Narration, Purpose_Cd, Charges_Amount, Collection_Amount, Entered_By, Entered_On)
--           VALUES (ResrvId, AdvDt, 'Booking Advance', 1, NULL, AdvAmt, UserId, NOW());
--        END IF;
--     END IF;

    IF CurPayAmt>0 Then
INSERT INTO trans_guest_ledger (Reservation_Id, Trans_Date, Narration, Purpose_Cd, Charges_Amount, Collection_Amount, Entered_By, Entered_On)
  VALUES (ResrvId, ChkInDt, 'Room Rent Advance', 2, NULL, CurPayAmt, UserId, NOW());
END IF;

INSERT INTO trans_guest_room (Reservation_Id, Room_Id, From_Date, Upto_Date, Extra_Bed_No, Entered_By, Entered_On)
  SELECT
    ResrvId,
    (SELECT
        Room_Id
      FROM mst_room_details
      WHERE Room_No = A.Room_No),
    ChkInDt,
    NULL,
    A.Extra_Bed,
    UserId,
    NOW()
  FROM tmp_room A
  ORDER BY SlNo;

UPDATE mst_room_details
SET Status = 2
WHERE Room_Id IN ((SELECT
    Room_Id
  FROM mst_room_details A,
       tmp_room B
  WHERE A.Room_No = B.Room_No));

  COMMIT;
SELECT
  ResrvNo AS RNo;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_GuestServices` (IN `OrdDate` DATE, IN `ResrvId` INT, IN `SrvId` SMALLINT, IN `ChrgAmt` DECIMAL(5,0), IN `Qty` TINYINT, IN `PayablAmt` DECIMAL(5,0), IN `Remkrs` VARCHAR(50), IN `OrdStat` TINYINT, IN `UsrId` SMALLINT)   BEGIN
    DECLARE Cnt INT;
 DECLARE SrvNm VARCHAR(50);
    
    SET SrvNm=( SELECT
    Service_Name
  FROM mst_paid_services
  WHERE Service_Id = SrvId);
    SET Cnt = ( SELECT
    COUNT(*)
  FROM trans_guest_reservation
  WHERE Reservation_Id = ResrvId);
    IF Cnt>0 THEN
      START TRANSACTION;
INSERT INTO trans_service_ordered (Order_Date, Reservation_Id, Service_Id, Charges_Amt, Quantity,
Payable_Amt, Remarks, Order_Status, Entered_By, Entered_On)
  VALUES (OrdDate, ResrvId, SrvId, ChrgAmt, Qty, PayablAmt, Remkrs, OrdStat, UsrId, NOW());

        IF OrdStat=2 THEN
INSERT INTO trans_guest_ledger (Reservation_Id, Trans_Date, Narration, Purpose_Cd, Charges_Amount, Collection_Amount, Entered_By, Entered_On)
  VALUES (ResrvId, OrdDate, SrvNm, 5, PayablAmt, NULL, UsrId, NOW());
END IF;

      COMMIT;
SELECT
  1 AS RetVal;
ELSE
SELECT
  0 AS RetVal;
END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_LogIn` (IN `UsrNm` VARCHAR(20), IN `UsrPwd` VARCHAR(20))  READS SQL DATA BEGIN
  DECLARE UsrId SMALLINT;

  Set UsrId=IFNULL(( SELECT
    User_Id
  FROM appl_users
  WHERE Usr_Name = UsrNm
  AND BINARY Usr_Pwd = UsrPwd
  AND Status = 1), 0);

  If UsrId=0 Then
SELECT
  0 AS UsrId,
  0 AS UsrGrpId,
  '' AS UsrNm;
ELSE
SELECT
  User_Id AS UsrId,
  UGrp_Id AS UsrGrpId,
  UFull_Name AS UsrNm
FROM appl_users
WHERE User_Id = UsrId;
END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_LogInMenus` (IN `UsrGrpId` SMALLINT)  READS SQL DATA BEGIN
  DECLARE Cnt SMALLINT;

  Set Cnt=( SELECT
    COUNT(*)
  FROM appl_user_grp_permission
  WHERE UGrp_Id = UsrGrpId);

  If Cnt=0 Then
SELECT
  0 AS MnuId,
  '' AS MnuNm,
  '' AS PgNm,
  '' AS PgPath;
ELSE
SELECT
  A.Menu_Id AS MnuId,
  Menu_Name AS MnuNm,
  Page_Name AS PgNm,
  Menu_Path AS PgPath
FROM appl_menus A,
     appl_user_grp_permission B
WHERE B.UGrp_Id = UsrGrpId
AND A.Menu_Id = B.Menu_Id
ORDER BY A.Menu_Id;
END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_MenuDtls` (IN `CatgId` SMALLINT, IN `MnuCd` SMALLINT, IN `MnuNm` VARCHAR(150), IN `MnuDesc` VARCHAR(255), IN `MnuRt` DECIMAL(6,2), IN `Stat` TINYINT)   BEGIN
  DECLARE Cnt INT;

  SET Cnt=( SELECT
    COUNT(*)
  FROM mst_menus
  WHERE Menu_Code = MnuCd);
  IF Cnt=0 THEN
    SET Cnt=( SELECT
    MAX(Menu_Id) + 1
  FROM mst_menus);

INSERT INTO mst_menus (Menu_Id, Category_Id, Menu_Code, Menu_Name, Menu_ShortNm, Menu_Desc, Rate, Status)
  VALUES (Cnt, CatgId, MnuCd, MnuNm, '', MnuDesc, MnuRt, Stat);
ELSE
UPDATE mst_menus
SET Category_Id = CatgId,
    Menu_Code = MnuCd,
    Menu_Name = MnuNm,
    Menu_Desc = MnuDesc,
    Rate = MnuRt,
    Status = Stat
WHERE Menu_Code = MnuCd;
END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_RmPackage` (IN `RTypeId` SMALLINT, IN `MonD` DECIMAL(5,0), IN `TueD` DECIMAL(5,0), IN `WedD` DECIMAL(5,0), IN `ThuD` DECIMAL(5,0), IN `FriD` DECIMAL(5,0), IN `SatD` DECIMAL(5,0), IN `SunD` DECIMAL(5,0))   BEGIN
UPDATE mst_room_rates_regular
SET Rate_Mon = MonD,
    Rate_Tue = TueD,
    Rate_Wed = WedD,
    Rate_Thu = ThuD,
    Rate_Fri = FriD,
    Rate_Sat = SatD,
    Rate_Sun = SunD
WHERE Room_TId = RTypeId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_RoomAllotment` (IN `AllotDt` DATE, IN `ReserveId` INT, IN `RmNo` VARCHAR(10), IN `ExtraCot` TINYINT, IN `UsrId` SMALLINT, OUT `RetVal` TINYINT)   BEGIN
  DECLARE Cnt INT;
 DECLARE RmId SMALLINT;
 DECLARE RetId TinyInt;
  SET RetVal=0;

  proc_label:BEGIN

      SET Cnt = ( SELECT
    COUNT(*)
  FROM trans_guest_reservation
  WHERE Reservation_Id = ReserveId);
      IF Cnt=0 THEN
        LEAVE proc_label;
      END IF;

      SET RmId = IFNULL(( SELECT
    Room_Id
  FROM mst_room_details
  WHERE Room_No = RmNo), 0);
      IF RmId=0 THEN
        LEAVE proc_label;
      END IF;

INSERT INTO trans_guest_room (Reservation_Id, Room_Id, From_Date, Upto_Date, Extra_Bed_No, Entered_By, Entered_On)
  VALUES (ReserveId, RmId, AllotDt, AllotDt, ExtraCot, UsrId, NOW());
      SET RetVal=1;
      
    END;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_RptBkCalendar` (IN `FrmDt` DATE, IN `ToDt` DATE)   BEGIN
    DECLARE RmCnt  TINYINT;
 DECLARE InDt DATE;

    DROP TEMPORARY TABLE IF EXISTS BkCalendar;

    CREATE TEMPORARY TABLE BkCalendar(RptDt DATE, RmCnt TINYINT);

    SET InDt=FrmDt;
    WHILE InDt<=ToDt DO
       SET RmCnt=IFNULL(( SELECT
    SUM(NoOf_Room)
  FROM trans_guest_booking
  WHERE InDt
  BETWEEN CheckIn_Date AND DATE_ADD(ExpChkOut_Dt, INTERVAL -1 DAY)), 0);
INSERT INTO BkCalendar
  VALUES (InDt, RmCnt);
       SET InDt=( SELECT
    DATE_ADD(InDt, INTERVAL 1 DAY));
END WHILE;

SELECT
  *
FROM BkCalendar
ORDER BY RptDt;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_RptBookingRegister` (IN `FrmDt` DATE, IN `ToDt` DATE)  READS SQL DATA BEGIN
SELECT
  DATE_FORMAT(CAST(Entered_On AS date), '%d-%m-%Y') AS BkDate,
  Booking_No AS BkNo,
  CONCAT(First_Name, ' ', Last_Name) AS GstNm,
  Contact_No AS ContNo,
  DATE_FORMAT(CheckIn_Date, '%d-%m-%Y') AS ChInDate,
  NoOf_Room AS NoOfRm,
  IFNULL(Advance_Amt, 0) AS AdvAmt,
  (SELECT
      Agent_Name
    FROM mst_agent
    WHERE Agent_Id = A.Agent_Id) AS AgntNm
/*(SELECT UFull_Name From appl_users Where User_Id=A.Entered_By) As UsrNm*/
FROM trans_guest_booking A
WHERE CAST(Entered_On AS date) BETWEEN FrmDt AND ToDt
ORDER BY CAST(Entered_On AS date), A.Booking_Id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_RptCollectionRegister` (IN `FrmDt` DATE, IN `ToDt` DATE)  READS SQL DATA BEGIN
SELECT
  DATE_FORMAT(Trans_Date, '%d-%m-%Y') AS CollDate,
  (SELECT
      Reservation_No
    FROM trans_guest_reservation
    WHERE Reservation_Id = A.Reservation_Id) AS ResrvNo,
  Narration AS Purpose,
  Collection_Amount AS CollAmt,
  (SELECT
      UFull_Name
    FROM appl_users
    WHERE User_Id = A.Entered_By) AS CollBy,
  DATE_FORMAT(Entered_On, '%d-%m-%Y %r') AS CollOn
FROM trans_guest_ledger A
WHERE Trans_Date BETWEEN FrmDt AND ToDt
ORDER BY Trans_Date, Ledger_Id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_RptExpensesRegister` (IN `FrmDt` DATE, IN `ToDt` DATE)  READS SQL DATA BEGIN
SELECT
  DATE_FORMAT(Exp_Date, '%d-%m-%Y') AS ExpDt,
  (SELECT
      Type_Name
    FROM mst_exp_type
    WHERE Exp_TId = A.Purpose_Cd) AS Purpose,
  Vendor_Name AS VndNm,
  Particulars AS Partcls,
  Bill_Amount AS BAmt,
  Paid_Amount AS PAmt,
  Due_Amount AS DAmt,
  (SELECT
      UFull_Name
    FROM appl_users
    WHERE User_Id = A.Entered_By) AS ExpBy,
  DATE_FORMAT(Entered_On, '%d-%m-%Y %r') AS ExpOn
FROM trans_expenses A
WHERE Exp_Date BETWEEN FrmDt AND ToDt
ORDER BY Exp_Date, Exp_Id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_RptGuestRegister` (IN `FrmDt` DATE, IN `ToDt` DATE)  READS SQL DATA BEGIN
SELECT
  DATE_FORMAT(CheckIn_Date, '%d-%m-%Y') AS RegDate,
  (SELECT
      Booking_No
    FROM trans_guest_booking
    WHERE Booking_Id = A.Booking_Id) AS BkNo,
  Reservation_No AS ResNo,
  CONCAT(First_Name, ' ', Last_Name) AS GstNm,
  Contact_No AS ContNo,
  Aadhar_No AS AdharNo,
  CONCAT(Address_1, ', ', Address_2) AS GstAdd,
  Guest_No AS NoofGust, /* NoOf_Room As NoofRm,*/
  (SELECT
      GROUP_CONCAT(Room_No)
    FROM trans_guest_room X,
         mst_room_details Y
    WHERE X.Reservation_Id = A.Reservation_Id
    AND Y.Room_Id = X.Room_Id) AS RoomNo,
  IFNULL(Advance_Amount, 0) AS AdvAmt,
  (SELECT
      Agent_Name
    FROM mst_agent
    WHERE Agent_Id = A.Agent_Id) AS AgntNm
/* (SELECT UFull_Name From appl_users Where User_Id=A.Entered_By) As UsrNm*/
FROM trans_guest_reservation A
WHERE CheckIn_Date BETWEEN FrmDt AND ToDt
ORDER BY CheckIn_Date, A.Reservation_Id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_RptInvoice` (IN `InvoiceNo` VARCHAR(25))   BEGIN

SELECT
  CONCAT(First_Name, ' ', Last_Name) AS GuestNm,
  Address_1 AS AddL1,
  Address_2 AS AddL2,
  CONCAT('Contact No. ', Contact_No) AS ContNo,
  B.Invoice_No AS InvNo,
  DATE_FORMAT(B.Checkout_Date, '%d-%m-%Y') AS InvDate,
  A.Reservation_No AS BkRefNo,
  DATE_FORMAT(A.CheckIn_Date, '%d-%m-%Y') AS RefDate,
  (SELECT
      Room_No
    FROM mst_room_details X,
         trans_guest_room Y
    WHERE Y.Reservation_Id = A.Reservation_Id
    AND X.Room_Id = Y.Room_Id
    ORDER BY Allotment_Id LIMIT 1) AS RmNo,
  'Room Rent',
  IFNULL((SELECT
      Room_Bill
    FROM trans_checkout_details
    WHERE Checkout_Id = B.Checkout_Id), 0) AS RoomAmt,
  'Food Bill',
  IFNULL((SELECT
      Food_Bill
    FROM trans_checkout_details
    WHERE Checkout_Id = B.Checkout_Id), 0) AS FoodAmt,
  'Service Bill',
  IFNULL((SELECT
      Service_Bill
    FROM trans_checkout_details
    WHERE Checkout_Id = B.Checkout_Id), 0) AS SrvAmt,
  IFNULL((SELECT
      Total_Bill
    FROM trans_checkout_details
    WHERE Checkout_Id = B.Checkout_Id), 0) AS SubTotAmt,
  ROUND(Gst_Amount / 2, 2) AS CGST,
  ROUND(Gst_Amount / 2, 2) AS SGST,
  ROUND(Gst_Amount, 2) AS GST,
  ROUND(Total_Amount, 2) AS TotAmount,
  (Round_Off + Disc_Amount + Amt_Paid) AS AdvAmt,
  Coll_Amount AS PayableAmt,
  DATE_FORMAT(A.CheckIn_Date, '%d-%m-%Y') AS ChkIn,
  TIME_FORMAT(A.Entered_On, '%h:%i %p') AS ChkInTime,
  DATE_FORMAT(B.Checkout_Date, '%d-%m-%Y') AS ChkOut,
  TIME_FORMAT(B.Entered_On, '%h:%i %p') AS ChkOutTime,
  DATEDIFF(B.Checkout_Date, B.CheckIn_Date) AS DaysNo,
  (SELECT
      COUNT(DISTINCT Room_Id)
    FROM trans_guest_room
    WHERE Reservation_Id = A.Reservation_Id) AS RoomNo,
  (SELECT
      SUM(Extra_Bed_No)
    FROM trans_guest_room
    WHERE Reservation_Id = A.Reservation_Id) AS ExtBed

FROM trans_guest_reservation A,
     trans_chekout_ledger B
WHERE B.Invoice_No = InvoiceNo
AND A.Reservation_Id = B.Reservation_Id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_RptKoT` (IN `KoT` INT)  READS SQL DATA BEGIN
SELECT
  C.Reservation_No,
  CONCAT(First_Name, Last_Name) AS CstNm,
  DATE_FORMAT(CheckIn_Date, '%d-%m-%Y') AS ChkDt,
  Room_No AS RmNo,
  CASE Room_Service WHEN 1 THEN 'Yes' ELSE 'No' END AS RmSrv,
  DATE_FORMAT(Order_Date, '%d-%m-%Y') AS OrdDt,
  Order_Time AS OrdTm,
  (SELECT
      Menu_Name
    FROM mst_menus
    WHERE Menu_Id = B.Menu_Id) AS MnuNm,
  B.Quantity AS Qty
FROM trans_food_ordered A,
     trans_food_ordered_detail B,
     trans_guest_reservation C
WHERE A.KOT_No = KoT
AND B.FoodOrd_Id = A.FoodOrd_Id
AND C.Reservation_Id = A.Reservation_Id
ORDER BY B.Order_Sl;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_SearchBookingNo` (IN `BookNo` INT)  READS SQL DATA BEGIN
SELECT
  Booking_Id AS Id,
  First_Name AS FstNm,
  Last_Name AS LstNm,
  Contact_No AS ContNo,
  Agent_Id AS AgntId,
  (SELECT
      Agent_Name
    FROM mst_agent
    WHERE Agent_Id = A.Agent_Id) AS AgntNm,
  IFNULL(Advance_Amt, 0) AS AdvAmt
FROM trans_guest_booking A
WHERE Booking_No = BookNo;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_SearchReservDtls` (IN `ResrvNo` INT, IN `RoomNo` VARCHAR(15))  READS SQL DATA BEGIN
  IF ResrvNo>0 THEN
SELECT
  Reservation_Id AS RsrvId,
  CONCAT(First_Name, ' ', Last_Name) AS GstNm,
  Contact_No AS PhNo,
  DATE_FORMAT(CheckIn_Date, '%d-%m-%Y') AS CkhDt,
  IFNULL((SELECT
      SUM(Collection_Amount)
    FROM trans_guest_ledger
    WHERE Reservation_Id = A.Reservation_Id), 0) AS PaidAmt
FROM trans_guest_reservation A
WHERE Reservation_No = ResrvNo;
ELSE
SELECT
  C.Reservation_Id AS RsrvId,
  CONCAT(First_Name, ' ', Last_Name) AS GstNm,
  Contact_No AS PhNo,
  DATE_FORMAT(C.CheckIn_Date, '%d-%m-%Y') AS CkhDt,
  IFNULL((SELECT
      SUM(Collection_Amount)
    FROM trans_guest_ledger
    WHERE Reservation_Id = C.Reservation_Id), 0) AS PaidAmt
FROM trans_guest_room A,
     mst_room_details B,
     trans_guest_reservation C
WHERE B.Room_No = RoomNo
AND A.Upto_Date IS NULL
AND A.Room_Id = B.Room_Id
AND C.Reservation_Id = A.Reservation_Id;
END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_ServiceDtls` (IN `SrvcId` SMALLINT, IN `SrvNm` VARCHAR(100), IN `SrvDesc` VARCHAR(150), IN `SrvChrgs` DECIMAL(5,2), IN `GstP` DECIMAL(5,2))   BEGIN
  DECLARE Cnt INT;

  SET Cnt=( SELECT
    COUNT(*)
  FROM mst_paid_services
  WHERE Service_Id = SrvcId);
  IF Cnt=0 THEN
INSERT INTO mst_paid_services (Service_Name, Serv_Desc, Serv_Charges, Gst_Prcnt, Status)
  VALUES (SrvNm, SrvDesc, SrvChrgs, GstP, 1);
ELSE
UPDATE mst_paid_services
SET Service_Name = SrvNm,
    Serv_Desc = SrvDesc,
    Serv_Charges = IFNULL(SrvChrgs, 0),
    Gst_Prcnt = IFNULL(GstP, 0)
WHERE Service_Id = SrvcId;
END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_SplRates` (IN `SplId` SMALLINT, IN `RTypeId` SMALLINT, IN `EvntNm` VARCHAR(100), IN `DtFrm` DATE, IN `DtTo` DATE, IN `SplRt` DECIMAL(5,0), IN `Sts` TINYINT)   BEGIN
  
    DECLARE Cnt INT;

    SET Cnt=( SELECT
    COUNT(*)
  FROM mst_room_rates_special
  WHERE Rate_Id_Spl = SplId);
    IF Cnt=0 THEN
INSERT INTO mst_room_rates_special (Room_TId, Event_Name, Date_From, Date_Upto, Rate_Spl, Status)
  VALUES (RTypeId, EvntNm, DtFrm, DtTo, SplRt, Sts);
ELSE
UPDATE mst_room_rates_special
SET Room_TId = RTypeId,
    Event_Name = EvntNm,
    Date_From = DtFrm,
    Date_Upto = DtTo,
    Rate_Spl = SplRt,
    Status = Sts
WHERE Rate_Id_Spl = SplId;
END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_StaffDtls` (IN `StfId` SMALLINT, `StfNm` VARCHAR(75), IN `NckNm` VARCHAR(25), IN `GurdNm` VARCHAR(75), IN `Addrs` VARCHAR(255), IN `ContP` VARCHAR(15), IN `ContS` VARCHAR(15), IN `GndrCd` TINYINT, IN `StfAge` VARCHAR(2), IN `AadharNo` VARCHAR(20), IN `JoinDt` DATE, IN `Desig` VARCHAR(25), IN `StaffSal` DECIMAL(6,0), IN `BnkDtls` VARCHAR(25), IN `Rmarks` VARCHAR(50), IN `Stats` TINYINT, IN `RelDate` DATE)   BEGIN
    DECLARE Cnt INT;
    
    SET Cnt = ( SELECT
    COUNT(*)
  FROM mst_staff
  WHERE Staff_Id = StfId);
    START TRANSACTION;
    IF Cnt=0 THEN
INSERT INTO mst_staff (Staff_Name, Nick_Name, Guardian_Name, Address, Cont_No_Primary, Cont_No_Second,
Gender_Cd, Age, Aadhar_No, Join_Date, Designation, Salary, Bank_Dtls, Remarks, Status, Release_Date)
  VALUES (StfNm, NckNm, GurdNm, Addrs, ContP, ContS, GndrCd, StfAge, AadharNo, JoinDt, Desig, IFNULL(StaffSal, 0), BnkDtls, Rmarks, Stats, IFNULL(RelDate, NULL));
ELSE
UPDATE mst_staff
SET Staff_Name = StfNm,
    Nick_Name = NckNm,
    Guardian_Name = GurdNm,
    Address = Addrs,
    Cont_No_Primary = ContP,
    Cont_No_Second = ContS,
    Gender_Cd = GndrCd,
    Age = StfAge,
    Aadhar_No = AadharNo,
    Join_Date = JoinDt,
    Designation = Desig,
    Salary = IFNULL(StaffSal, 0),
    Bank_Dtls = BnkDtls,
    Remarks = Rmarks,
    Status = Stats,
    Release_Date = IFNULL(RelDate, NULL)
WHERE Staff_Id = StfId;
END IF;
    COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_User` (IN `UsrId` SMALLINT, `GrpId` SMALLINT, `UFullNm` VARCHAR(50), `UsrNm` VARCHAR(20), `UsrPwd` VARCHAR(20), `Stats` TINYINT)   BEGIN
  DECLARE Cnt INT;

  SET Cnt=( SELECT
    COUNT(*)
  FROM appl_users
  WHERE User_Id = UsrId);
  IF Cnt=0 THEN
    SET Cnt=( SELECT
    MAX(User_Id) + 1
  FROM appl_users);

INSERT INTO appl_users (User_Id, UGrp_Id, UFull_Name, Usr_Name, Usr_Pwd, Status, Created_On)
  VALUES (Cnt, GrpId, UFullNm, UsrNm, UsrPwd, 1, NOW());
ELSE
UPDATE appl_users
SET UGrp_Id = GrpId,
    UFull_Name = UFullNm,
    Usr_Name = UsrNm,
    Usr_Pwd = UsrPwd,
    Created_On = NOW()
WHERE User_Id = UsrId;
END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_ViewAgentDtlsList` ()  READS SQL DATA BEGIN
SELECT
  -- ROW_NUMBER() OVER (Order By Agent_Id) As Sl,
  Agent_Id AS Sl,
  Agent_Name AS AgntNm,
  Org_Name AS Org,
  Address AS Addrs,
  Contact_No AS ContNo,
  PAN_No AS Pan,
  Comm_Prcnt AS Prcnt
FROM mst_agent
WHERE Status = 1
ORDER BY Agent_Id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_ViewBookingList` ()  READS SQL DATA BEGIN
SELECT
  DATE_FORMAT(CheckIn_Date, '%d-%m-%y') AS CkhDt,
  Booking_No AS BkNo,
  CONCAT(First_Name, ' ', Last_Name) AS CustNm,
  Contact_No AS CntNo,
  (SELECT
      Room_TName
    FROM mst_room_type
    WHERE Room_TId = A.Room_TId) AS RmTyp,
  NoOf_Room AS NoOfRoom,
  CONCAT(Stay_Duration, ' Days') AS Dura,
  IFNULL(Adult, 0) AS Adlt,
  IFNULL(Child, 0) AS Chld,
  (SELECT
      Agent_Name
    FROM mst_agent
    WHERE Agent_Id = A.Agent_Id) AS AgntNm,
  Note AS Rem,
  (SELECT
      Opt_Description
    FROM appl_options
    WHERE Opt_Grp_Id = 11
    AND Opt_Code = A.Status) AS BkStatus,
  (SELECT
      UFull_Name
    FROM appl_users
    WHERE User_Id = A.Entered_By) AS Usr,
  DATE_FORMAT(Entered_On, '%d-%m-%y %l:%i') AS EntOn
FROM trans_guest_booking A
ORDER BY CheckIn_Date DESC, Booking_Id DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_ViewCheckOutList` ()  READS SQL DATA BEGIN
SELECT
  Invoice_No AS InvNo,
  Reservation_No AS RsrvNo,
  CONCAT(First_Name, ' ', Last_Name) AS GstNm,
  Total_Amount AS BilAmt,
  Disc_Amount AS DisAmt,
  (Total_Amount - Disc_Amount) AS NetAmt
FROM trans_chekout_ledger A,
     trans_guest_reservation B
WHERE A.Checkout_Date = DATE_FORMAT(NOW(), '%Y-%m-%d')
AND B.Reservation_Id = A.Reservation_Id
ORDER BY Checkout_Id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_ViewExpList` ()  READS SQL DATA BEGIN
SELECT
  Exp_Id AS Id,
  DATE_FORMAT(Exp_Date, '%d-%m-%Y') AS ExpDt,
  (SELECT
      Type_Name
    FROM mst_exp_type
    WHERE Exp_TId = A.Purpose_Cd) AS Purpose,
  Vendor_Name AS VndNm,
  Bill_Amount AS BAmt
FROM trans_expenses A
ORDER BY Exp_Date DESC, Exp_Id DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_ViewExpTypList` ()  READS SQL DATA BEGIN
SELECT
  ROW_NUMBER() OVER (
  ORDER BY Exp_TId) AS Sl,
  Type_Name AS TypNm
FROM mst_exp_type
WHERE Status = 1
ORDER BY Exp_TId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_ViewFoodOrderList` ()  READS SQL DATA BEGIN
SELECT
  DATE_FORMAT(Order_Date, '%d-%m-%Y') AS OrdDt,
  KOT_No AS KotNo,
  Reservation_No AS ResrvNo,
  Room_No AS RmNo,
  CASE Room_Service WHEN 1 THEN 'Yes' ELSE 'No' END AS RmServ,
  TIME_FORMAT(A.Entered_On, '%h:%i') AS OrdTime
FROM trans_food_ordered A,
     trans_guest_reservation B
WHERE A.Order_Status = 1
AND B.Reservation_Id = A.Reservation_Id
ORDER BY Order_Date, FoodOrd_Id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_ViewMenuCatgList` ()  READS SQL DATA BEGIN
SELECT
  ROW_NUMBER() OVER (
  ORDER BY Category_Id) AS Sl,
  CASE Type_Code WHEN 1 THEN 'Package' ELSE 'A la carte' END AS Typ,
  Categ_Name AS CatNm,
  GST
FROM mst_menu_category
ORDER BY Category_Id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_ViewMenuDtlsList` ()  READS SQL DATA BEGIN
SELECT
  ROW_NUMBER() OVER (
  ORDER BY Menu_Id) AS Sl,
  Categ_Name AS CatNm,
  Menu_Code AS MCd,
  Menu_Name AS MnuNm,
  Menu_Desc AS MnuDesc,
  Rate AS MRate
FROM mst_menus A,
     mst_menu_category B
WHERE B.Category_Id = A.Category_Id
AND Status = 1
ORDER BY Menu_Id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_ViewPaymentList` ()  READS SQL DATA BEGIN
SELECT
  Reservation_No AS RsrvNo,
  CONCAT(First_Name, ' ', Last_Name) AS GstNm,
  (SELECT
      Opt_Description
    FROM appl_options
    WHERE Opt_Grp_Id = 12
    AND Opt_Code = A.Purpose_Cd) AS PayPurpose,
  Collection_Amount AS CollAmt
FROM trans_guest_ledger A,
     trans_guest_reservation B
WHERE A.Trans_Date = DATE_FORMAT(NOW(), '%Y-%m-%d')
AND B.Reservation_Id = A.Reservation_Id
ORDER BY Ledger_Id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_ViewReservationList` ()  READS SQL DATA BEGIN
SELECT
  DATE_FORMAT(CheckIn_Date, '%d-%m-%y') AS CkhDt,
  Reservation_No AS ResNo,
  CONCAT(First_Name, ' ', Last_Name) AS CustNm,
  Contact_No AS CntNo,
  CONCAT(Days_No, ' Days') AS Dura,
  DATE_FORMAT(CheckOut_Date, '%d-%m-%y') AS CkhODt,
  NoOf_Room AS NoOfRoom,
  Room_Rent AS RentAmt,
  IFNULL(Advance_Amount, 0) AS AdvAmt,
  (SELECT
      Agent_Name
    FROM mst_agent
    WHERE Agent_Id = A.Agent_Id) AS AgntNm,
  (SELECT
      Opt_Description
    FROM appl_options
    WHERE Opt_Grp_Id = 11
    AND Opt_Code = A.Status) AS BkStatus,
  (SELECT
      UFull_Name
    FROM appl_users
    WHERE User_Id = A.Entered_By) AS Usr,
  DATE_FORMAT(Entered_On, '%d-%m-%y %l:%i') AS EntOn
FROM trans_guest_reservation A
ORDER BY CheckIn_Date DESC, Booking_Id DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_ViewResortList` ()  READS SQL DATA BEGIN
SELECT
  Resort_Name AS RstNm,
  Company_Name AS CmpNm,
  Address_1 AS Add1,
  Address_2 AS Add2,
  Phone AS Contact,
  Email AS eMl,
  Website AS Web,
  PAN_No AS Pan,
  GST_No AS Gst,
  Header_Text AS Disptxt
FROM mst_resort_setup;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_ViewRoomDtlsList` ()  READS SQL DATA BEGIN
SELECT
  ROW_NUMBER() OVER (
  ORDER BY Room_Id) AS Sl,
  Room_TName AS Typ,
  Room_No AS RNo,
  Room_Desc AS RDesc
FROM mst_room_details A,
     mst_room_type B
WHERE B.Room_TId = A.Room_Type
AND A.Status = 1
ORDER BY Room_Id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_ViewRoomPriceList` ()  READS SQL DATA BEGIN
SELECT
  ROW_NUMBER() OVER (
  ORDER BY A.Room_TId) AS Sl,
  Room_TName AS TypNm,
  Rate_Mon AS Mon,
  Rate_Tue AS Tue,
  Rate_Wed AS Wed,
  Rate_Thu AS Thu,
  Rate_Fri AS Fri,
  Rate_Sat AS Sat,
  Rate_Sun AS Sun,
  Extra_Bed_Charges AS ExtraBed,
  GST
FROM mst_room_type A,
     mst_room_rates_regular B
WHERE B.Room_TId = A.Room_TId
AND A.Status = 1
ORDER BY A.Room_TId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_ViewRoomTypList` ()  READS SQL DATA BEGIN
SELECT
  ROW_NUMBER() OVER (
  ORDER BY Room_TId) AS Sl,
  Room_TName AS TypNm,
  Extra_Bed_Charges AS ExtraBed,
  GST
FROM mst_room_type
WHERE Status = 1
ORDER BY Room_TId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_ViewServiceOrderList` ()  READS SQL DATA BEGIN
SELECT
  DATE_FORMAT(Order_Date, '%d-%m-%Y') AS OrdDt,
  Reservation_No AS ResrvNo,
  (SELECT
      Room_No
    FROM mst_room_details X,
         trans_guest_room Y
    WHERE Y.Reservation_Id = A.Reservation_Id
    AND Y.Room_Id = X.Room_Id
    AND Upto_Date IS NULL
    ORDER BY From_Date DESC LIMIT 1) AS RmNo,
  CONCAT(First_Name, ' ', Last_Name) AS CustNm,
  B.Service_Name AS SrvNm
FROM trans_service_ordered A,
     mst_paid_services B,
     trans_guest_reservation C
WHERE A.Order_Status = 1
AND B.Service_Id = A.Service_Id
AND C.Reservation_Id = A.Reservation_Id
ORDER BY Order_Date, ServOrd_Id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_ViewServicesList` ()  READS SQL DATA BEGIN
SELECT
  Service_Id AS Sl,
  Service_Name AS SrvNm,
  Serv_Desc AS SrvDesc,
  Serv_Charges AS SrvChrg,
  Gst_Prcnt AS Gst
FROM mst_paid_services
WHERE Status = 1
ORDER BY Service_Id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_ViewSettingsList` ()  READS SQL DATA BEGIN
SELECT
  Invoice_Start AS Inv,
  Advance_Amount AS Adv,
  (SELECT
      Opt_Description
    FROM appl_options
    WHERE Opt_Grp_Id = 1
    AND Opt_Code = A.Pay_Type_Cd) AS Typ,
  CheckIn_Time AS Ckhin,
  CheckOut_Time AS ChkOut,
  CASE Booking_Allowed WHEN 1 THEN 'Yes' ELSE 'No' END AS BkAllow
FROM app_settings A;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_ViewSplPriceList` ()  READS SQL DATA BEGIN
SELECT
  ROW_NUMBER() OVER (
  ORDER BY A.Room_TId) AS Sl,
  Room_TName AS TypNm,
  Event_Name AS EvntNm,
  DATE_FORMAT(Date_From, '%d-%m-%YY') AS FrmDt,
  DATE_FORMAT(Date_Upto, '%d-%m-%YY') AS ToDt,
  Rate_Spl AS SplRt,
  Rate_Id_Spl AS Id
FROM mst_room_type A,
     mst_room_rates_special B
WHERE B.Room_TId = A.Room_TId
AND B.Status = 1
ORDER BY B.Date_From, B.Room_TId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_ViewStaffList` ()  READS SQL DATA BEGIN
SELECT
  Staff_Id AS Sl,
  Staff_Name AS StfNm,
  Guardian_Name AS GurdNm,
  Address AS Addrs,
  Cont_No_Primary AS ContNo,
  Age,
  Blood_Grp AS BG,
  Aadhar_No AS UID,
  Designation AS Desig,
  DATE_FORMAT(Join_Date, '%d-%M-%Y') AS JoinDt
FROM mst_staff
WHERE Status = 1
ORDER BY Staff_Id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_ViewUsrDtlsList` ()  READS SQL DATA BEGIN
SELECT
  ROW_NUMBER() OVER (
  ORDER BY User_Id) AS Sl,
  UFull_Name AS FullNm,
  Usr_Name AS UsrNm,
  B.UGrp_Name AS GrpNm
FROM appl_users A,
     appl_user_group B
WHERE B.UGrp_Id = A.UGrp_Id
AND A.Status = 1
ORDER BY User_Id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_ViewUsrGroupList` ()  READS SQL DATA BEGIN
SELECT
  ROW_NUMBER() OVER (
  ORDER BY UGrp_Id) AS Sl,
  UGrp_Name AS GrpNm,
  UGrp_Description AS GrpDesc,
  CASE Is_Admin WHEN 1 THEN 'Yes' ELSE 'No' END AS Admn
FROM appl_user_group
WHERE Is_Active = 1
ORDER BY UGrp_Id;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `appl_menus`
--

CREATE TABLE `appl_menus` (
  `Menu_Id` smallint(6) NOT NULL,
  `Parent_Menu` varchar(100) DEFAULT NULL,
  `Menu_Code` varchar(10) DEFAULT NULL,
  `Menu_Name` varchar(100) DEFAULT NULL,
  `Page_Name` varchar(25) DEFAULT NULL,
  `Menu_Path` varchar(255) DEFAULT NULL,
  `Status` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB AVG_ROW_LENGTH=455 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `appl_menus`
--

INSERT INTO `appl_menus` (`Menu_Id`, `Parent_Menu`, `Menu_Code`, `Menu_Name`, `Page_Name`, `Menu_Path`, `Status`) VALUES
(1, 'Setup', 'M1', 'User Group', 'P1', 'localhost', 1),
(2, 'Setup', 'M2', 'User Details', 'P2', 'localhost', 1),
(3, 'Setup', 'M3', 'Room Type', 'P3', 'localhost', 1),
(4, 'Setup', 'M4', 'Room Details', 'P4', 'localhost', 1),
(5, 'Setup', 'M5', 'Price Manager', 'P5', 'localhost', 1),
(6, 'Setup', 'M6', 'Menu Category', 'P6', 'localhost', 1),
(7, 'Setup', 'M7', 'Menu Details', 'P7', 'localhost', 1),
(8, 'Setup', 'M8', 'Travel Agent', 'P8', 'localhost', 1),
(9, 'Setup', 'M9', 'Paid Services', 'P9', 'localhost', 1),
(10, 'Setup', 'M10', 'Employees', 'P10', 'localhost', 1),
(11, 'Setup', 'M11', 'Coupon Management', 'P11', 'localhost', 1),
(12, 'Setup', 'M12', 'Expenses Type', 'P12', 'localhost', 1),
(13, 'Setup', 'M13', 'Resort Setup', 'P13', 'localhost', 1),
(14, 'Setup', 'M14', 'Settings', 'P14', 'localhost', 1),
(15, 'Setup', 'M15', 'Backup', 'P15', 'localhost', 1),
(16, 'Guest', 'M16', 'Booking', 'P16', 'localhost', 1),
(17, 'Guest', 'M17', 'Reservation/Allotment', 'P17', 'localhost', 1),
(18, 'Guest', 'M18', 'Add Payments', 'P18', 'localhost', 1),
(19, 'Guest', 'M19', 'Food Order', 'P19', 'localhost', 1),
(20, 'Guest', 'M20', 'Service Order', 'P20', 'localhost', 1),
(21, 'Guest', 'M21', 'Booking Extend', 'P21', 'localhost', 1),
(22, 'Guest', 'M22', 'Checkout & Billing', 'P22', 'localhost', 1),
(23, 'Guest', 'M23', 'Invoices', 'P23', 'localhost', 1),
(24, 'Inhouse', 'M24', 'Housekeeping', 'P24', 'localhost', 1),
(25, 'Inhouse', 'M25', 'Restaurant Order', 'P25', 'localhost', 1),
(26, 'Inhouse', 'M26', 'Expenses', 'P26', 'localhost', 1),
(27, 'Inhouse', 'M27', 'Stock', 'P27', 'localhost', 1),
(28, 'Inhouse', 'M28', 'Complain', 'P28', 'localhost', 1),
(29, 'Reports', 'M29', 'Booked Rooms', 'P29', 'localhost', 1),
(30, 'Reports', 'M30', 'Available Rooms', 'P30', 'localhost', 1),
(31, 'Reports', 'M31', 'Occupancy Report', 'P31', 'localhost', 1),
(32, 'Reports', 'M32', 'Availability Calendar', 'P32', 'localhost', 1),
(33, 'Reports', 'M33', 'Collection Ledger', 'P33', 'localhost', 1),
(34, 'Reports', 'M34', 'Expenses Ledger', 'P34', 'localhost', 1),
(35, 'Reports', 'M35', 'Agent Commsssion', 'P35', 'localhost', 1),
(36, 'Reports', 'M36', 'Car Hire Charges', 'P36', 'localhost', 1);

-- --------------------------------------------------------

--
-- Table structure for table `appl_options`
--

CREATE TABLE `appl_options` (
  `Option_Id` smallint(6) NOT NULL,
  `Opt_Grp_Id` smallint(6) NOT NULL,
  `Opt_Group` varchar(100) NOT NULL,
  `Opt_Code` tinyint(4) NOT NULL,
  `Opt_LongDesc` varchar(150) DEFAULT NULL,
  `Opt_Description` varchar(100) NOT NULL,
  `Is_Active` bit(1) NOT NULL,
  `Srl_No` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB AVG_ROW_LENGTH=81 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `appl_options`
--

INSERT INTO `appl_options` (`Option_Id`, `Opt_Grp_Id`, `Opt_Group`, `Opt_Code`, `Opt_LongDesc`, `Opt_Description`, `Is_Active`, `Srl_No`) VALUES
(1, 1, 'Amount Type', 1, 'Percentage', '%', b'1', 1),
(2, 1, 'Amount Type', 2, 'Fixed', 'Fixed', b'1', 2),
(3, 2, 'Status', 1, NULL, 'Active', b'1', 1),
(4, 2, 'Status', 2, NULL, 'Inactive', b'1', 2),
(5, 3, 'Option', 1, NULL, 'Yes', b'1', 1),
(6, 3, 'Option', 2, NULL, 'No', b'1', 2),
(7, 4, 'Menu Type', 1, NULL, 'Package', b'1', 1),
(8, 4, 'Menu Type', 2, NULL, ' A la carte', b'1', 2),
(9, 5, 'Gender', 1, 'M', 'Male', b'1', 1),
(10, 5, ' Gender', 2, 'F', 'Female', b'1', 2),
(11, 5, 'Gender', 3, 'T', 'Transgender', b'1', 3),
(12, 6, 'Coupon', 1, NULL, 'Food', b'1', 1),
(13, 6, 'Coupon', 2, NULL, 'Accomodation', b'1', 2),
(14, 6, 'Coupon', 3, NULL, 'All', b'1', 3),
(15, 7, 'Mode', 1, NULL, 'Cash', b'1', 1),
(16, 7, 'Mode', 2, NULL, 'UPI', b'1', 2),
(17, 7, 'Mode', 3, NULL, 'Card', b'1', 3),
(18, 8, 'Service Status', 1, NULL, 'Ordered', b'1', 1),
(19, 8, 'Service Status', 2, NULL, 'Executed', b'1', 2),
(20, 8, 'Service Status', 3, NULL, 'Cancelled', b'1', 3),
(21, 9, 'KOT Status', 1, NULL, 'Ordered', b'1', 1),
(22, 9, 'KOT Status', 2, NULL, 'Delivered', b'1', 2),
(23, 9, 'KOT Status', 3, NULL, 'Cancelled', b'1', 3),
(24, 10, 'Room Status', 1, NULL, 'Available', b'1', 1),
(25, 10, 'Room Status', 2, NULL, 'Booked', b'1', 2),
(26, 11, 'Booking Status', 1, NULL, 'Pencil Booking', b'1', 1),
(27, 11, 'Booking Status', 2, NULL, 'Requested', b'1', 2),
(28, 11, 'Booking Status', 3, NULL, 'Cancelled', b'1', 3),
(29, 11, 'Booking Status', 4, NULL, 'Executed', b'1', 4),
(30, 12, 'Billing', 1, NULL, 'Advance', b'1', 1),
(31, 12, 'Billing', 2, NULL, 'Adhoc Payment', b'1', 2),
(32, 12, 'Billing', 3, NULL, 'Final Payment', b'1', 3);

-- --------------------------------------------------------

--
-- Table structure for table `appl_users`
--

CREATE TABLE `appl_users` (
  `User_Id` smallint(6) NOT NULL,
  `UGrp_Id` smallint(6) NOT NULL,
  `UFull_Name` varchar(50) NOT NULL,
  `Usr_Name` varchar(20) NOT NULL,
  `Usr_Pwd` varchar(20) NOT NULL,
  `Status` tinyint(4) NOT NULL,
  `Created_On` datetime DEFAULT NULL
) ENGINE=InnoDB AVG_ROW_LENGTH=16384 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `appl_users`
--

INSERT INTO `appl_users` (`User_Id`, `UGrp_Id`, `UFull_Name`, `Usr_Name`, `Usr_Pwd`, `Status`, `Created_On`) VALUES
(1, 1, 'System Manager', 'Sysadmin', '12345', 1, NULL),
(2, 2, 'RSR Chowdhury', 'Frontway', '12345', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `appl_user_group`
--

CREATE TABLE `appl_user_group` (
  `UGrp_Id` smallint(6) NOT NULL,
  `UGrp_Name` varchar(50) NOT NULL,
  `UGrp_Description` varchar(150) DEFAULT NULL,
  `Is_Admin` bit(1) DEFAULT NULL,
  `Is_Active` bit(1) NOT NULL
) ENGINE=InnoDB AVG_ROW_LENGTH=8192 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `appl_user_group`
--

INSERT INTO `appl_user_group` (`UGrp_Id`, `UGrp_Name`, `UGrp_Description`, `Is_Admin`, `Is_Active`) VALUES
(1, 'Super Admin', 'System Manager', b'1', b'1'),
(2, 'Admin', 'Administrator', b'1', b'1'),
(3, 'Front Desk', 'Operation Manager', b'0', b'1');

-- --------------------------------------------------------

--
-- Table structure for table `appl_user_grp_permission`
--

CREATE TABLE `appl_user_grp_permission` (
  `Perm_Id` int(11) NOT NULL,
  `UGrp_Id` smallint(6) NOT NULL,
  `Menu_Id` smallint(6) NOT NULL,
  `Is_Active` bit(1) DEFAULT NULL
) ENGINE=InnoDB AVG_ROW_LENGTH=4096 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `appl_user_grp_permission`
--

INSERT INTO `appl_user_grp_permission` (`Perm_Id`, `UGrp_Id`, `Menu_Id`, `Is_Active`) VALUES
(1, 2, 1, b'1'),
(2, 2, 2, b'1'),
(3, 2, 3, b'1'),
(4, 2, 4, b'1'),
(5, 2, 5, b'1'),
(6, 2, 6, b'1'),
(7, 2, 7, b'1'),
(8, 2, 8, b'1'),
(9, 2, 9, b'1'),
(10, 2, 10, b'1'),
(11, 2, 11, b'1'),
(12, 2, 12, b'1'),
(13, 2, 13, b'1'),
(14, 2, 14, b'1'),
(15, 2, 15, b'1'),
(16, 2, 16, b'1'),
(17, 2, 17, b'1'),
(18, 2, 18, b'1'),
(19, 2, 19, b'1'),
(20, 2, 20, b'1'),
(21, 2, 21, b'1'),
(22, 2, 22, b'1'),
(23, 2, 23, b'1'),
(24, 2, 24, b'1'),
(25, 2, 25, b'1'),
(26, 2, 26, b'1'),
(27, 2, 27, b'1'),
(28, 2, 28, b'1'),
(29, 2, 29, b'1'),
(30, 2, 30, b'1'),
(31, 2, 31, b'1'),
(32, 2, 32, b'1'),
(33, 2, 33, b'1'),
(34, 2, 34, b'1'),
(35, 2, 35, b'1'),
(36, 2, 36, b'1'),
(37, 3, 16, b'1'),
(38, 3, 17, b'1'),
(39, 3, 18, b'1'),
(40, 3, 19, b'1'),
(41, 3, 20, b'1'),
(42, 3, 21, b'1'),
(43, 3, 22, b'1'),
(44, 3, 23, b'1'),
(45, 3, 24, b'1'),
(46, 3, 25, b'1'),
(47, 3, 26, b'1'),
(48, 3, 27, b'1'),
(49, 3, 28, b'1'),
(50, 3, 29, b'1'),
(51, 3, 30, b'1'),
(52, 3, 31, b'1'),
(53, 3, 32, b'1'),
(54, 3, 33, b'1'),
(55, 3, 34, b'1'),
(56, 3, 35, b'1'),
(57, 3, 36, b'1');

-- --------------------------------------------------------

--
-- Table structure for table `app_settings`
--

CREATE TABLE `app_settings` (
  `Invoice_No` smallint(6) DEFAULT NULL,
  `Financial_Year` varchar(10) DEFAULT NULL,
  `Year_St` date DEFAULT NULL,
  `Year_End` date DEFAULT NULL,
  `Advance_Amount` decimal(8,2) DEFAULT NULL,
  `Pay_Type_Cd` tinyint(4) DEFAULT NULL,
  `CheckIn_Time` varchar(25) DEFAULT NULL,
  `CheckOut_Time` varchar(25) DEFAULT NULL,
  `Booking_Allowed` bit(1) DEFAULT NULL,
  `Booking_Msg` varchar(150) DEFAULT NULL,
  `Checkout_Msg` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `app_settings`
--

INSERT INTO `app_settings` (`Invoice_No`, `Financial_Year`, `Year_St`, `Year_End`, `Advance_Amount`, `Pay_Type_Cd`, `CheckIn_Time`, `CheckOut_Time`, `Booking_Allowed`, `Booking_Msg`, `Checkout_Msg`) VALUES
(4, '2024-25', '2024-04-01', '2025-03-31', 10.00, 1, '11.00 AM', '09:00 AM', b'1', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `mst_agent`
--

CREATE TABLE `mst_agent` (
  `Agent_Id` smallint(6) NOT NULL,
  `Agent_Name` varchar(100) DEFAULT NULL,
  `Org_Name` varchar(150) DEFAULT NULL,
  `Address` varchar(200) DEFAULT NULL,
  `Contact_No` varchar(25) DEFAULT NULL,
  `GST_No` varchar(25) DEFAULT NULL,
  `PAN_No` varchar(15) DEFAULT NULL,
  `Comm_Prcnt` decimal(5,2) DEFAULT NULL,
  `Status` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB AVG_ROW_LENGTH=8192 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `mst_agent`
--

INSERT INTO `mst_agent` (`Agent_Id`, `Agent_Name`, `Org_Name`, `Address`, `Contact_No`, `GST_No`, `PAN_No`, `Comm_Prcnt`, `Status`) VALUES
(1, 'Amar Bengal Tourism', NULL, 'Dum Dum Cantonment, Subhash Nagar, Kolkata, West Bengal 700065', '79807 27789/94330 34772', NULL, NULL, 10.00, 1),
(2, 'Dibyendu Banerjee', 'Jhargram Tourism', 'Ghoradhara, Jhargram, India, West Bengal', '95938 33711', NULL, NULL, 5.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `mst_coupon`
--

CREATE TABLE `mst_coupon` (
  `Coupon_Id` smallint(6) NOT NULL,
  `Coupon_Cd` varchar(15) DEFAULT NULL,
  `Title` varchar(50) DEFAULT NULL,
  `Discount` decimal(6,2) DEFAULT NULL,
  `Disc_Type_Code` tinyint(4) DEFAULT NULL,
  `Effect_On_Code` tinyint(4) DEFAULT NULL,
  `Valid_Till` date DEFAULT NULL,
  `Status` tinyint(4) DEFAULT NULL,
  `Entered_By` smallint(6) DEFAULT NULL,
  `Entered_On` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mst_exp_type`
--

CREATE TABLE `mst_exp_type` (
  `Exp_TId` smallint(6) NOT NULL,
  `Type_Name` varchar(75) DEFAULT NULL,
  `Status` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB AVG_ROW_LENGTH=2730 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `mst_exp_type`
--

INSERT INTO `mst_exp_type` (`Exp_TId`, `Type_Name`, `Status`) VALUES
(1, 'Salaries + Benefits', 1),
(2, 'Advertising', 1),
(3, 'Supplies', 1),
(4, 'Internet + Telephone', 1),
(5, 'Electricity', 1),
(6, 'Repairing + Maintenance', 1),
(8, 'Due Paid', 1),
(25, 'Other Expenses', 1);

-- --------------------------------------------------------

--
-- Table structure for table `mst_menus`
--

CREATE TABLE `mst_menus` (
  `Menu_Id` smallint(6) NOT NULL DEFAULT 0,
  `Category_Id` smallint(6) DEFAULT NULL,
  `Menu_Code` smallint(6) DEFAULT NULL,
  `Menu_Name` varchar(150) DEFAULT NULL,
  `Menu_ShortNm` varchar(25) DEFAULT NULL,
  `Menu_Desc` varchar(255) DEFAULT NULL,
  `Rate` decimal(6,2) DEFAULT NULL,
  `Status` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB AVG_ROW_LENGTH=151 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `mst_menus`
--

INSERT INTO `mst_menus` (`Menu_Id`, `Category_Id`, `Menu_Code`, `Menu_Name`, `Menu_ShortNm`, `Menu_Desc`, `Rate`, `Status`) VALUES
(1, 1, 10101, 'Breakfast', NULL, 'Puri Sabji (6 pcs) or Toast (4 pcs Butter/Jam), Boiled Egg (1 pcs) Or Omlette or Egg Poach (1 pcs),\nTea (Black/Milk), Sweet, Fruits', 200.00, 1),
(2, 1, 10201, 'Lunch', NULL, 'Steamed Plain Rice/Tawa Roti, Yellow Dal, Aloo Bhaja, Aloo Poshto/Potol Poshto, Fish Curry (Rui/Katla 1 Pcs) or Egg Curry (2Pcs) or Paneer Butter Masala, Chutney, Papad', 350.00, 1),
(3, 1, 10301, 'Dinner', NULL, 'Steamed Plain Rice/Tawa Roti, Yellow Dal, Mixed Veg Curry, Chicken Curry (4 Pcs) or Egg Curry (2Pcs) or Paneer Butter Masala, Green Salad, Sweet', 350.00, 1),
(4, 1, 10202, 'Special Veg Lunch', NULL, 'Basmati Rice, Ghee, Shukto, Jhur Jhure Aloo Bhaja, Yellow Dal, Aloo Poshto, Patal Korma/Aloo Phulkopi, Paneer Butter Masala, Chutney, Papad', 450.00, 1),
(5, 1, 10203, 'Special Non-Veg Lunch', NULL, 'Basmati Rice, Ghee, Shukto, Jhur Jhure Aloo Bhaja, Yellow Dal, Aloo Poshto, Patal Korma/Aloo Phulkopi, Chutney, Papad, Fish Curry (Rui/Katla 1Pc), Sorshe Maachh - Parshe /Pabda (1 Pc) or Egg Curry (2 Pcs)', 750.00, 1),
(6, 1, 10302, 'Special Non-Veg Dinner', NULL, 'Basmati Rice or Luchi (6 Pcs), Yellow Dal, Mixed Veg Curry, Desi Murgi Kosha (4 Pcs) or Mutton Kosha (4 Pcs), Green Salad, Sweet', 850.00, 1),
(7, 2, 20101, 'Toast Butter/Jam', NULL, '4 Pcs', 45.00, 1),
(8, 2, 20102, 'Luchi Tarkari', NULL, '4 pcs', 80.00, 1),
(9, 2, 20103, 'Grilled Sandwich - Veg', NULL, NULL, 60.00, 1),
(10, 2, 20104, 'Grilled Sandwich - Chicken', NULL, NULL, 100.00, 1),
(11, 2, 20105, 'Cornflakes with Milk', NULL, NULL, 80.00, 1),
(12, 2, 20106, 'Maggi Noodles - Masala', NULL, '2 Maggi Packets per Plate', 80.00, 1),
(13, 2, 20107, 'Maggi Noodles - Egg', NULL, '2 Maggi Packets per Plate', 100.00, 1),
(14, 2, 20108, 'Boiled Egg', NULL, '2 Eggs', 45.00, 1),
(15, 2, 20109, 'Double Egg Omlette', NULL, NULL, 80.00, 1),
(16, 2, 20110, 'Egg Poach', NULL, '2 Eggs', 80.00, 1),
(17, 3, 20401, 'Tomato Soup', NULL, NULL, 100.00, 1),
(18, 3, 20402, 'Mixed Vegetable Soup', NULL, NULL, 150.00, 1),
(19, 3, 20403, 'Sweet Corn Soup - Veg', NULL, NULL, 130.00, 1),
(20, 3, 20404, 'Sweet Corn Soup - Chicken', NULL, NULL, 145.00, 1),
(21, 3, 20405, 'Chicken Soup', NULL, NULL, 200.00, 1),
(22, 4, 20501, 'French Fry', NULL, NULL, 80.00, 1),
(23, 4, 20502, 'Aloo Pakora', NULL, NULL, 40.00, 1),
(24, 4, 20503, 'Vegetable Pakora', NULL, NULL, 80.00, 1),
(25, 4, 20504, 'Onion Pakora', NULL, NULL, 80.00, 1),
(26, 4, 20505, 'Paneer Pakora', NULL, '10 pcs', 160.00, 1),
(27, 4, 20506, 'Paneer Bhurji', NULL, NULL, 120.00, 1),
(28, 4, 20507, 'Chilli Paneer Dry', NULL, NULL, 150.00, 1),
(29, 4, 20508, 'Baked Papad', NULL, '2 Pc', 30.00, 1),
(30, 4, 20509, 'Fried  Papad', NULL, '2 Pc', 50.00, 1),
(31, 4, 20510, 'Green Salad', NULL, NULL, 50.00, 1),
(32, 5, 20601, 'Egg Pakora', NULL, '4 Eggs', 110.00, 1),
(33, 5, 20602, 'Egg Bhurji', NULL, '4 Eggs', 80.00, 1),
(34, 5, 20603, 'Chilli Fish', NULL, '6 Pcs', 150.00, 1),
(35, 5, 20604, 'Fish Finger with Kasundi', NULL, '10 Pcs', 180.00, 1),
(36, 5, 20605, 'Local Fried Fish', NULL, '2 Medium Pcs', 120.00, 1),
(37, 5, 20606, 'Fried Ilish (Seasonal)', NULL, '2 Pcs', 500.00, 1),
(38, 5, 20607, 'Chicken Pakora', NULL, '8 Pcs', 120.00, 1),
(39, 5, 20608, 'Chilli Chicken Dry - With Bone', NULL, '8 Pcs', 160.00, 1),
(40, 5, 20609, 'Chilli Chicken Dry - Boneless', NULL, '8 Pcs', 175.00, 1),
(41, 5, 20610, 'Gandhoraj Chicekn Dry', NULL, '8 Pcs', 160.00, 1),
(42, 5, 20611, 'Chicken Seekh Kabab', NULL, '4 Pcs', 200.00, 1),
(43, 5, 20612, 'Campfire with Barbeque Chicken', NULL, '1 Kg', 1200.00, 1),
(44, 5, 20613, 'Campfire with Barbeque Chicken', NULL, '1.5 Kg', 1600.00, 1),
(45, 6, 20701, 'Tawa Roti', NULL, 'Per Pc', 10.00, 1),
(46, 6, 20702, 'Tawa Roti with Butter', NULL, 'Per Pc', 20.00, 1),
(47, 6, 20703, 'Plain Paratha', NULL, 'Per Pc', 15.00, 1),
(48, 6, 20704, 'Aloo Paratha with Pickle', NULL, '2 Pc', 80.00, 1),
(49, 6, 20705, 'Luchi', NULL, '4 Pc', 50.00, 1),
(50, 7, 20801, 'Steamed Rice - Basmati', NULL, NULL, 75.00, 1),
(51, 7, 20802, 'Steamed Rice - Gobindobhog', NULL, NULL, 90.00, 1),
(52, 7, 20803, 'Steamed Plain Rice', NULL, NULL, 50.00, 1),
(53, 7, 20804, 'Jeera Rice', NULL, NULL, 60.00, 1),
(54, 7, 20805, 'Veg Fried Rice', NULL, NULL, 110.00, 1),
(55, 7, 20806, 'Egg Fried Rice', NULL, NULL, 130.00, 1),
(56, 7, 20807, 'Chicken Fried Rice', NULL, NULL, 160.00, 1),
(57, 7, 20808, 'Mixed Fried Rice', NULL, NULL, 170.00, 1),
(58, 7, 20809, 'Peas Pulao', NULL, NULL, 95.00, 1),
(59, 7, 20810, 'Veg Hakka Noodles', NULL, NULL, 100.00, 1),
(60, 7, 20811, 'Egg Hakka Noodles', NULL, NULL, 120.00, 1),
(61, 7, 20812, 'Chicken Hakka Noodles', NULL, NULL, 150.00, 1),
(62, 7, 20813, 'Mixed Hakka Noodles', NULL, NULL, 170.00, 1),
(63, 8, 20901, 'Moong Dal', NULL, NULL, 85.00, 1),
(64, 8, 20902, 'Machher Matha Diye Moong Dal', NULL, NULL, 120.00, 1),
(65, 8, 20903, 'Aada Mouri diye Beulir Dal', NULL, NULL, 120.00, 1),
(66, 8, 20904, 'Musoor Dal', NULL, NULL, 85.00, 1),
(67, 8, 20905, 'Shukto', NULL, NULL, 120.00, 1),
(68, 8, 20906, 'Begun Bhaja', NULL, '2 Pcs', 80.00, 1),
(69, 8, 20907, 'Jhur Jhure Aloo Bhaja', NULL, 'with Peanuts', 80.00, 1),
(70, 8, 20908, 'Jeera Aloo', NULL, NULL, 80.00, 1),
(71, 8, 20909, 'Aloo Mutter', NULL, NULL, 120.00, 1),
(72, 8, 20910, 'Aloo Phulkopi', NULL, NULL, 120.00, 1),
(73, 8, 20911, 'Kashmiri Dum Aloo', NULL, NULL, 150.00, 1),
(74, 8, 20912, 'Aloo Posto', NULL, NULL, 120.00, 1),
(75, 8, 20913, 'Shojne Datta diye Posto', NULL, 'Seasonal', 120.00, 1),
(76, 8, 20914, 'Potol Posto', NULL, NULL, 120.00, 1),
(77, 8, 20915, 'Posto Bora', NULL, '3 Pcs', 150.00, 1),
(78, 8, 20916, 'Veg Manchurian', NULL, NULL, 130.00, 1),
(79, 8, 20917, 'Lal Sakh  with Bori', NULL, NULL, 50.00, 1),
(80, 8, 20918, 'Local  Sakh (Seasonal)', NULL, NULL, 50.00, 1),
(81, 8, 20919, 'Mutter Paner', NULL, NULL, 130.00, 1),
(82, 8, 20920, 'Kadai Paneer', NULL, NULL, 130.00, 1),
(83, 8, 20921, 'Paneer Butter Masala', NULL, NULL, 150.00, 1),
(84, 8, 20922, 'Dry Chilli Paneer', NULL, NULL, 150.00, 1),
(85, 8, 20923, 'Gravy Chilli Paneer', NULL, NULL, 180.00, 1),
(86, 9, 21001, 'Egg Curry', NULL, '2 Eggs', 120.00, 1),
(87, 9, 21002, 'Fish Kalia (Rui/ Katla)', NULL, '2 Pcs', 180.00, 1),
(88, 9, 21003, 'Doi Maachh (Rui/ Katla)', NULL, '2 Pcs', 190.00, 1),
(89, 9, 21004, 'Shorshe Maachh - Katla', NULL, '2 Pcs', 185.00, 1),
(90, 9, 21005, 'Shorshe Maachh - Parshe', NULL, '2 Pcs', 175.00, 1),
(91, 9, 21006, 'Shorshe Maachh - Pabda', NULL, '2 Pcs (Seasonal)', 230.00, 1),
(92, 9, 21007, 'Shorshe Maachh - Pomfret', NULL, '2 Pcs (Seasonal)', 250.00, 1),
(93, 9, 21008, 'Shorshe Maachh - Ilish', NULL, '2 Pcs (Seasonal)', 550.00, 1),
(94, 9, 21009, 'Pabda Aloo Bori Begun Diye Jhol', NULL, '2 Pcs (Seasonal)', 230.00, 1),
(95, 9, 21010, 'Ilish Begun Kachkola Diye Jhol', NULL, '2 Pcs (Seasonal)', 550.00, 1),
(96, 9, 21011, 'Dry Chilli Chicken (With Bone)', NULL, '8 Pcs', 160.00, 1),
(97, 9, 21012, 'Gravy Chilli Chicken (With Bone)', NULL, '8 Pcs', 200.00, 1),
(98, 9, 21013, 'Chicken Curry', NULL, '4 Pc', 150.00, 1),
(99, 9, 21014, 'Chicken Do Pyaza', NULL, '4 Pc', 160.00, 1),
(100, 9, 21015, 'Chicken Butter Masala', NULL, '2 Pcs', 180.00, 1),
(101, 9, 21016, 'Desi Murgi Kosha (on Request)', NULL, '4 Pcs', 260.00, 1),
(102, 9, 21017, 'Mutton Kosha', NULL, '4 Pcs', 350.00, 1),
(103, 9, 21018, 'Thakurbarir Kochi Pathar Jhol (On Request)', NULL, '4 Pcs', 400.00, 1),
(104, 10, 21101, 'Mixed Chutney', NULL, NULL, 40.00, 1),
(105, 10, 21102, 'Tomato Chutney', NULL, NULL, 40.00, 1),
(106, 10, 21103, 'Kaju Aamsottor Chutney', NULL, NULL, 80.00, 1),
(107, 11, 21201, 'Black Tea', NULL, NULL, 15.00, 1),
(108, 11, 21202, 'Green Tea', NULL, NULL, 30.00, 1),
(109, 11, 21203, 'Milk Tea', NULL, NULL, 25.00, 1),
(110, 11, 21204, 'Black Coffee', NULL, NULL, 20.00, 1),
(111, 11, 21205, 'Milk Coffee', NULL, NULL, 40.00, 1),
(112, 11, 21206, 'Fresh Lime Soda', NULL, NULL, 80.00, 1),
(113, 11, 21207, 'Fruit Juice', NULL, NULL, 60.00, 1),
(114, 11, 21208, 'Aerated Soft Drinks ', NULL, NULL, 40.00, 1),
(115, 11, 21209, 'Packaged Mineral Water - 500 ML', NULL, NULL, 10.00, 1),
(116, 11, 21210, 'Packaged Mineral Water - 1 LTR', NULL, NULL, 20.00, 1),
(117, 11, 21211, 'Packaged Mineral Water - 2 LTR', NULL, NULL, 30.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `mst_menu_category`
--

CREATE TABLE `mst_menu_category` (
  `Category_Id` smallint(6) NOT NULL,
  `Categ_Name` varchar(50) DEFAULT NULL,
  `Type_Code` tinyint(4) DEFAULT NULL,
  `GST` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB AVG_ROW_LENGTH=1489 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `mst_menu_category`
--

INSERT INTO `mst_menu_category` (`Category_Id`, `Categ_Name`, `Type_Code`, `GST`) VALUES
(1, 'Package Menu', 1, 12.00),
(2, 'Breakfast', 2, 12.00),
(3, 'Soup', 2, 12.00),
(4, 'Appetizer - Veg', 2, 12.00),
(5, 'Appetizer - Non Veg', 2, 12.00),
(6, 'Indian Flat Bread', 2, 12.00),
(7, 'Rice & Noodles', 2, 12.00),
(8, 'Main Course - Veg', 2, 12.00),
(9, 'Main Course - Non Veg', 2, 12.00),
(10, 'Chutney', 2, 12.00),
(11, 'Beverage', 2, 12.00);

-- --------------------------------------------------------

--
-- Table structure for table `mst_miscinfo`
--

CREATE TABLE `mst_miscinfo` (
  `RmSrv_Chrgs` decimal(4,2) DEFAULT NULL,
  `RmRent_GST` decimal(4,2) DEFAULT NULL,
  `FoodBill_GST` decimal(4,2) DEFAULT NULL,
  `OthrSrv_GST` decimal(4,2) DEFAULT NULL
) ENGINE=InnoDB AVG_ROW_LENGTH=16384 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `mst_miscinfo`
--

INSERT INTO `mst_miscinfo` (`RmSrv_Chrgs`, `RmRent_GST`, `FoodBill_GST`, `OthrSrv_GST`) VALUES
(10.00, 24.00, 24.00, 24.00);

-- --------------------------------------------------------

--
-- Table structure for table `mst_paid_services`
--

CREATE TABLE `mst_paid_services` (
  `Service_Id` smallint(6) NOT NULL,
  `Service_Name` varchar(100) DEFAULT NULL,
  `Serv_Desc` varchar(150) DEFAULT NULL,
  `Serv_Charges` decimal(5,0) DEFAULT NULL,
  `Gst_Prcnt` decimal(5,2) DEFAULT NULL,
  `Status` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB AVG_ROW_LENGTH=1489 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `mst_paid_services`
--

INSERT INTO `mst_paid_services` (`Service_Id`, `Service_Name`, `Serv_Desc`, `Serv_Charges`, `Gst_Prcnt`, `Status`) VALUES
(1, 'Corkage Fee', '2 Glass, 1 Water Bottle', 100, 12.00, 1),
(2, 'EV Charging/Hour Basis', NULL, 150, 12.00, 1),
(3, 'EV Charging/Unit Basis', NULL, 25, 12.00, 1),
(4, 'Pickup/Drop (Car) - Barabhum', '20 Km', 1000, 12.00, 1),
(5, 'Pickup/Drop (Car) - Purulia', '52 Km', 2000, 12.00, 1),
(6, 'Sightseeing Package#1', 'Ajodhya hill top, Bamni Falls, PPSP Uper Dam, PPSPLower Dam, Loharia Temple, Kesto Bazar Dam', 0, 12.00, 1),
(7, 'Sightseeing Package#2', 'Ajodhya range covering Charida Village, Matha Forest, Pakhi Pahar.', 0, 12.00, 1),
(8, 'Sightseeing Package#3', 'Turga Falls, Blue Lake(Echo Point), Bamni Falls, Mayur Pahar', 0, 12.00, 1),
(9, 'Sightseeing Package#4', 'Murguma Dam, Deulghata Temple', 0, 12.00, 1),
(10, 'Sightseeing Package#5', 'Muraddi Dam(Baranti), Garhpanchakot, Panchet Dam', 0, 12.00, 1),
(11, 'Sightseeing Package#6', 'Raghunathpur range covering Joychandi Pahar', 0, 12.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `mst_resort_setup`
--

CREATE TABLE `mst_resort_setup` (
  `Resort_Name` varchar(255) DEFAULT NULL,
  `Company_Name` varchar(255) DEFAULT NULL,
  `Comp_Logo` longblob DEFAULT NULL,
  `Address_1` varchar(255) DEFAULT NULL,
  `Address_2` varchar(255) DEFAULT NULL,
  `Phone` varchar(35) DEFAULT NULL,
  `Email` varchar(50) DEFAULT NULL,
  `Website` varchar(50) DEFAULT NULL,
  `PAN_No` varchar(15) DEFAULT NULL,
  `GST_No` varchar(20) DEFAULT NULL,
  `Header_Text` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `mst_resort_setup`
--

INSERT INTO `mst_resort_setup` (`Resort_Name`, `Company_Name`, `Comp_Logo`, `Address_1`, `Address_2`, `Phone`, `Email`, `Website`, `PAN_No`, `GST_No`, `Header_Text`) VALUES
('SONKUPI FOREST RESORT', 'A Unit of Royal Redstone Hotels And Resorts Pvt. Ltd.', NULL, 'Duarshini More, Sonkupi Village, Post - Matha Forest', 'Baghmundi, Purulia, West Bengal - 723152', '+ 91 98300 50135 / + 91 93307 62095', 'royalredstone04@gmail.com', 'https://www.sonkupiforestresort.com/', NULL, NULL, 'Sonkupi Forest Resort\r\n(A Unit of Royal Redstone Hotels And Resorts Pvt. Ltd.)\r\nDuarshini More, Sonkupi Village, Post - Matha Forest,\r\nBaghmundi, Purulia - 723152, West Bengal');

-- --------------------------------------------------------

--
-- Table structure for table `mst_room_details`
--

CREATE TABLE `mst_room_details` (
  `Room_Id` smallint(6) NOT NULL,
  `Room_Type` smallint(6) DEFAULT NULL,
  `Room_No` varchar(10) DEFAULT NULL,
  `Room_Desc` varchar(150) DEFAULT NULL,
  `Adult` tinyint(4) DEFAULT NULL,
  `Child` tinyint(4) DEFAULT NULL,
  `Booking_Allowed` tinyint(4) DEFAULT NULL,
  `Status` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB AVG_ROW_LENGTH=1489 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `mst_room_details`
--

INSERT INTO `mst_room_details` (`Room_Id`, `Room_Type`, `Room_No`, `Room_Desc`, `Adult`, `Child`, `Booking_Allowed`, `Status`) VALUES
(1, 1, '101', 'Cottage', 2, 1, 1, 2),
(2, 1, '102', 'Cottage', 2, 1, 1, 2),
(3, 1, '103', 'Cottage', 2, 1, 1, 2),
(4, 1, '104', 'Cottage', 2, 1, 1, 2),
(5, 1, '105', 'Cottage', 2, 1, 1, 1),
(6, 1, '106', 'Cottage', 2, 1, 1, 1),
(7, 1, '107', 'Cottage', 2, 1, 1, 1),
(8, 1, '108', 'Cottage', 2, 1, 2, NULL),
(9, 1, '109', 'Cottage', 2, 1, 2, NULL),
(10, 1, '110', 'Cottage', 2, 1, 2, NULL),
(11, 1, '111', 'Cottage', 2, 1, 2, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `mst_room_rates_regular`
--

CREATE TABLE `mst_room_rates_regular` (
  `Rate_Id_Reg` smallint(6) NOT NULL,
  `Room_TId` smallint(6) NOT NULL,
  `Rate_Mon` decimal(5,0) DEFAULT NULL,
  `Rate_Tue` decimal(5,0) DEFAULT NULL,
  `Rate_Wed` decimal(5,0) DEFAULT NULL,
  `Rate_Thu` decimal(5,0) DEFAULT NULL,
  `Rate_Fri` decimal(5,0) DEFAULT NULL,
  `Rate_Sat` decimal(5,0) DEFAULT NULL,
  `Rate_Sun` decimal(5,0) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `mst_room_rates_regular`
--

INSERT INTO `mst_room_rates_regular` (`Rate_Id_Reg`, `Room_TId`, `Rate_Mon`, `Rate_Tue`, `Rate_Wed`, `Rate_Thu`, `Rate_Fri`, `Rate_Sat`, `Rate_Sun`) VALUES
(1, 1, 3750, 3750, 3750, 3750, 3750, 3750, 3750);

-- --------------------------------------------------------

--
-- Table structure for table `mst_room_rates_special`
--

CREATE TABLE `mst_room_rates_special` (
  `Rate_Id_Spl` smallint(6) NOT NULL,
  `Room_TId` smallint(6) NOT NULL,
  `Event_Name` varchar(100) DEFAULT NULL,
  `Date_From` date DEFAULT NULL,
  `Date_Upto` date DEFAULT NULL,
  `Rate_Spl` decimal(5,0) DEFAULT NULL,
  `Status` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mst_room_type`
--

CREATE TABLE `mst_room_type` (
  `Room_TId` smallint(6) NOT NULL,
  `Room_TName` varchar(150) DEFAULT NULL,
  `Extra_Bed_Charges` decimal(6,2) DEFAULT NULL,
  `Charges_Type_Code` tinyint(4) DEFAULT NULL,
  `GST` decimal(5,2) DEFAULT NULL,
  `Status` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `mst_room_type`
--

INSERT INTO `mst_room_type` (`Room_TId`, `Room_TName`, `Extra_Bed_Charges`, `Charges_Type_Code`, `GST`, `Status`) VALUES
(1, 'Deluxe Cottage', 1050.00, 2, 12.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `mst_staff`
--

CREATE TABLE `mst_staff` (
  `Staff_Id` smallint(6) NOT NULL,
  `Staff_Name` varchar(75) DEFAULT NULL,
  `Nick_Name` varchar(25) DEFAULT NULL,
  `Guardian_Name` varchar(75) DEFAULT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `Cont_No_Primary` varchar(15) DEFAULT NULL,
  `Cont_No_Second` varchar(15) DEFAULT NULL,
  `Gender_Cd` tinyint(4) DEFAULT NULL,
  `Age` varchar(2) DEFAULT NULL,
  `Blood_Grp` varchar(10) DEFAULT NULL,
  `Aadhar_No` varchar(20) DEFAULT NULL,
  `Join_Date` date DEFAULT NULL,
  `Designation` varchar(25) DEFAULT NULL,
  `Salary` decimal(6,0) DEFAULT NULL,
  `Bank_Dtls` varchar(25) DEFAULT NULL,
  `Remarks` varchar(50) DEFAULT NULL,
  `Status` tinyint(4) DEFAULT NULL,
  `Release_Date` date DEFAULT NULL
) ENGINE=InnoDB AVG_ROW_LENGTH=8192 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `mst_staff`
--

INSERT INTO `mst_staff` (`Staff_Id`, `Staff_Name`, `Nick_Name`, `Guardian_Name`, `Address`, `Cont_No_Primary`, `Cont_No_Second`, `Gender_Cd`, `Age`, `Blood_Grp`, `Aadhar_No`, `Join_Date`, `Designation`, `Salary`, `Bank_Dtls`, `Remarks`, `Status`, `Release_Date`) VALUES
(1, 'Cooking Staff', 'Boy-1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Service', 1, NULL),
(2, 'Service Staff', 'Boy-2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Service', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tmp_bill`
--

CREATE TABLE `tmp_bill` (
  `Bill_Amt` decimal(10,2) DEFAULT NULL,
  `Gst_Amt` decimal(6,2) DEFAULT NULL,
  `Tot_Amount` decimal(10,2) DEFAULT NULL,
  `Roff_Amt` decimal(4,2) DEFAULT NULL,
  `Net_Amt` decimal(10,2) DEFAULT NULL,
  `Amt_Paid` decimal(10,2) DEFAULT NULL,
  `Amt_Due` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tmp_bill`
--

INSERT INTO `tmp_bill` (`Bill_Amt`, `Gst_Amt`, `Tot_Amount`, `Roff_Amt`, `Net_Amt`, `Amt_Paid`, `Amt_Due`) VALUES
(3750.00, 900.00, 4650.00, 0.00, 4650.00, 1100.00, 3550.00);

-- --------------------------------------------------------

--
-- Table structure for table `tmp_billfood`
--

CREATE TABLE `tmp_billfood` (
  `SlNo` smallint(6) NOT NULL,
  `RoomNo` varchar(10) DEFAULT NULL,
  `OrdDt` date DEFAULT NULL,
  `KOT_No` int(11) DEFAULT NULL,
  `BillAmt` decimal(5,0) DEFAULT NULL,
  `SrvAmt` decimal(4,0) DEFAULT NULL,
  `TotAmount` decimal(5,0) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tmp_billroom`
--

CREATE TABLE `tmp_billroom` (
  `SlNo` smallint(6) NOT NULL,
  `RoomNo` varchar(10) DEFAULT NULL,
  `FrmDt` date DEFAULT NULL,
  `ToDate` date DEFAULT NULL,
  `DaysNo` smallint(6) DEFAULT NULL,
  `RmRate` decimal(4,0) DEFAULT NULL,
  `RmAmt` decimal(5,0) DEFAULT NULL,
  `ExtCotAmt` decimal(4,0) DEFAULT NULL,
  `TotAmount` decimal(5,0) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tmp_billroom`
--

INSERT INTO `tmp_billroom` (`SlNo`, `RoomNo`, `FrmDt`, `ToDate`, `DaysNo`, `RmRate`, `RmAmt`, `ExtCotAmt`, `TotAmount`) VALUES
(1, '104', '2024-10-28', '2024-10-28', 1, 3750, 3750, 0, 3750);

-- --------------------------------------------------------

--
-- Table structure for table `tmp_billservice`
--

CREATE TABLE `tmp_billservice` (
  `SlNo` smallint(6) NOT NULL,
  `SrvDate` date DEFAULT NULL,
  `ServiceId` smallint(6) DEFAULT NULL,
  `SrvAmount` decimal(5,0) DEFAULT NULL,
  `SrvQty` tinyint(4) DEFAULT NULL,
  `TotAmount` decimal(5,0) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tmp_food`
--

CREATE TABLE `tmp_food` (
  `SlNo` tinyint(4) NOT NULL,
  `Memu_Id` smallint(6) DEFAULT NULL,
  `Quantity` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tmp_room`
--

CREATE TABLE `tmp_room` (
  `SlNo` tinyint(4) NOT NULL,
  `Book_Date` date DEFAULT NULL,
  `Room_No` varchar(10) DEFAULT NULL,
  `Extra_Bed` tinyint(4) DEFAULT NULL,
  `Days_No` tinyint(4) DEFAULT NULL,
  `Chrg_Amt` decimal(5,0) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tmp_room`
--

INSERT INTO `tmp_room` (`SlNo`, `Book_Date`, `Room_No`, `Extra_Bed`, `Days_No`, `Chrg_Amt`) VALUES
(1, '2024-10-28', '104', 0, 1, 3750);

-- --------------------------------------------------------

--
-- Table structure for table `trans_agent_ledger`
--

CREATE TABLE `trans_agent_ledger` (
  `Ledger_Id` int(11) NOT NULL,
  `Agent_Id` smallint(6) DEFAULT NULL,
  `Trans_Date` date DEFAULT NULL,
  `Naration` varchar(50) DEFAULT NULL,
  `Reservation_Id` int(11) DEFAULT NULL,
  `Collection_Amount` decimal(8,0) DEFAULT NULL,
  `Comm_Prcnt` decimal(5,2) DEFAULT NULL,
  `Comm_Amount` decimal(5,0) DEFAULT NULL,
  `Payment_Amount` decimal(5,0) DEFAULT NULL,
  `Outs_Amount` decimal(8,0) DEFAULT NULL,
  `Entered_By` smallint(6) DEFAULT NULL,
  `Entered_On` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trans_checkout_details`
--

CREATE TABLE `trans_checkout_details` (
  `Checkout_Id` int(11) NOT NULL,
  `Room_Bill` decimal(10,2) DEFAULT NULL,
  `Food_Bill` decimal(10,2) DEFAULT NULL,
  `Service_Bill` decimal(10,2) DEFAULT NULL,
  `Total_Bill` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `trans_checkout_details`
--

INSERT INTO `trans_checkout_details` (`Checkout_Id`, `Room_Bill`, `Food_Bill`, `Service_Bill`, `Total_Bill`) VALUES
(7, 3750.00, 220.00, 100.00, 4070.00),
(8, 3750.00, 132.00, 0.00, 3882.00),
(9, 3750.00, 176.00, 0.00, 3926.00),
(10, 3750.00, 0.00, 0.00, 3750.00);

-- --------------------------------------------------------

--
-- Table structure for table `trans_chekout_ledger`
--

CREATE TABLE `trans_chekout_ledger` (
  `Checkout_Id` int(11) NOT NULL,
  `Reservation_Id` int(11) DEFAULT NULL,
  `Invoice_No` varchar(25) DEFAULT NULL,
  `CheckIn_Date` date DEFAULT NULL,
  `Checkout_Date` date DEFAULT NULL,
  `Bill_Amount` decimal(10,2) DEFAULT NULL,
  `Gst_Amount` decimal(6,2) DEFAULT NULL,
  `Total_Amount` decimal(10,2) DEFAULT NULL,
  `Round_Off` decimal(4,2) DEFAULT NULL,
  `Net_Amount` decimal(10,2) DEFAULT NULL,
  `Disc_Amount` decimal(6,2) DEFAULT NULL,
  `Amt_Paid` decimal(10,2) DEFAULT NULL,
  `Coll_Amount` decimal(10,2) DEFAULT NULL,
  `Due_Amount` decimal(10,2) DEFAULT NULL,
  `Remarks` varchar(50) DEFAULT NULL,
  `Entered_By` smallint(6) DEFAULT NULL,
  `Entered_On` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `trans_chekout_ledger`
--

INSERT INTO `trans_chekout_ledger` (`Checkout_Id`, `Reservation_Id`, `Invoice_No`, `CheckIn_Date`, `Checkout_Date`, `Bill_Amount`, `Gst_Amount`, `Total_Amount`, `Round_Off`, `Net_Amount`, `Disc_Amount`, `Amt_Paid`, `Coll_Amount`, `Due_Amount`, `Remarks`, `Entered_By`, `Entered_On`) VALUES
(7, 1, '00001/2024-25', '2024-10-06', '2024-10-07', 4070.00, 976.80, 5046.80, 0.20, 5047.00, 47.00, 1000.00, 4000.00, 0.00, '', 1, '2024-10-06 08:31:10'),
(8, 2, '00002/2024-25', '2024-10-06', '2024-10-06', 3882.00, 931.68, 4813.68, 0.32, 4814.00, 14.00, 1000.00, 3800.00, 0.00, '', 1, '2024-10-06 08:34:23'),
(9, 3, '00003/2024-25', '2024-10-08', '2024-10-08', 3926.00, 942.24, 4868.24, 0.24, 4868.00, 0.00, 1000.00, 3868.00, 0.00, '', 1, '2024-10-08 22:20:47'),
(10, 4, '00004/2024-25', '2024-10-28', '2024-10-28', 3750.00, 900.00, 4650.00, 0.00, 4650.00, 0.00, 1100.00, 3550.00, 0.00, '', 1, '2024-10-28 21:44:01');

-- --------------------------------------------------------

--
-- Table structure for table `trans_expenses`
--

CREATE TABLE `trans_expenses` (
  `Exp_Id` int(11) NOT NULL,
  `Exp_Date` date DEFAULT NULL,
  `Purpose_Cd` tinyint(4) DEFAULT NULL,
  `Vendor_Name` varchar(75) DEFAULT NULL,
  `Particulars` longtext DEFAULT NULL,
  `Bill_Amount` decimal(10,2) DEFAULT NULL,
  `Paid_Amount` decimal(10,2) DEFAULT NULL,
  `Due_Amount` decimal(10,2) DEFAULT NULL,
  `Entered_By` smallint(6) DEFAULT NULL,
  `Entered_On` datetime DEFAULT NULL
) ENGINE=InnoDB AVG_ROW_LENGTH=16384 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `trans_expenses`
--

INSERT INTO `trans_expenses` (`Exp_Id`, `Exp_Date`, `Purpose_Cd`, `Vendor_Name`, `Particulars`, `Bill_Amount`, `Paid_Amount`, `Due_Amount`, `Entered_By`, `Entered_On`) VALUES
(1, '2025-02-05', 3, 'M/S ', 'Grocessary', 1500.00, 1200.00, 300.00, 1, '2025-02-06 18:01:47');

-- --------------------------------------------------------

--
-- Table structure for table `trans_food_ordered`
--

CREATE TABLE `trans_food_ordered` (
  `FoodOrd_Id` int(11) NOT NULL,
  `Order_Date` date DEFAULT NULL,
  `Order_Time` varchar(15) DEFAULT NULL,
  `KOT_No` int(11) DEFAULT NULL,
  `Reservation_Id` int(11) DEFAULT NULL,
  `Room_Service` bit(1) DEFAULT NULL,
  `Room_No` varchar(10) DEFAULT NULL,
  `Special_Ins` varchar(100) DEFAULT NULL,
  `Order_Status` tinyint(4) DEFAULT NULL,
  `Delivered_By` varchar(75) DEFAULT NULL,
  `Entered_By` smallint(6) DEFAULT NULL,
  `Entered_On` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `trans_food_ordered`
--

INSERT INTO `trans_food_ordered` (`FoodOrd_Id`, `Order_Date`, `Order_Time`, `KOT_No`, `Reservation_Id`, `Room_Service`, `Room_No`, `Special_Ins`, `Order_Status`, `Delivered_By`, `Entered_By`, `Entered_On`) VALUES
(1, '2024-10-06', '07:42', 241, 1, b'1', '101', '', 1, '1', 1, '2024-10-06 07:42:13'),
(2, '2024-10-06', '07:43', 242, 1, b'1', '', '', 1, '', 1, '2024-10-06 07:43:25'),
(3, '2024-10-06', '08:33', 243, 2, b'1', '102', '', 1, '1', 1, '2024-10-06 08:33:48'),
(4, '2024-10-08', '10:20', 244, 3, b'1', '103', 'ok', 1, '1', 1, '2024-10-08 22:20:20'),
(5, '2024-10-28', '09:42', 245, 1, b'1', '101', '', 1, '1', 1, '2024-10-28 21:42:06'),
(6, '2024-11-04', '09:20', 246, 0, b'0', '', '', 1, '', 1, '2024-11-04 09:20:23'),
(7, '2024-11-05', '10:56', 247, 0, b'0', '', '', 1, '', 1, '2024-11-05 22:56:41'),
(8, '2024-11-05', '10:56', 248, 0, b'0', '', '', 1, '', 1, '2024-11-05 22:56:43');

-- --------------------------------------------------------

--
-- Table structure for table `trans_food_ordered_detail`
--

CREATE TABLE `trans_food_ordered_detail` (
  `Order_Sl` int(11) NOT NULL,
  `FoodOrd_Id` int(11) DEFAULT NULL,
  `Menu_Id` smallint(6) DEFAULT NULL,
  `Quantity` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `trans_food_ordered_detail`
--

INSERT INTO `trans_food_ordered_detail` (`Order_Sl`, `FoodOrd_Id`, `Menu_Id`, `Quantity`) VALUES
(1, 1, 65, 1),
(2, 2, 68, 1),
(3, 3, 65, 1),
(4, 4, 114, 1),
(5, 4, 74, 1),
(6, 5, 65, 1);

-- --------------------------------------------------------

--
-- Table structure for table `trans_guest_booking`
--

CREATE TABLE `trans_guest_booking` (
  `Booking_Id` int(11) NOT NULL,
  `Booking_No` int(11) DEFAULT NULL,
  `First_Name` varchar(75) DEFAULT NULL,
  `Last_Name` varchar(75) DEFAULT NULL,
  `Contact_No` varchar(15) DEFAULT NULL,
  `Room_TId` smallint(6) DEFAULT NULL,
  `NoOf_Room` tinyint(4) DEFAULT NULL,
  `CheckIn_Date` date DEFAULT NULL,
  `Stay_Duration` tinyint(4) DEFAULT NULL,
  `ExpChkOut_Dt` date DEFAULT NULL,
  `Adult` tinyint(4) DEFAULT NULL,
  `Child` tinyint(4) DEFAULT NULL,
  `Agent_Id` smallint(6) DEFAULT NULL,
  `Serv_Id` smallint(6) DEFAULT NULL,
  `Adv_Date` date DEFAULT NULL,
  `Advance_Amt` decimal(5,0) DEFAULT NULL,
  `Note` varchar(255) DEFAULT NULL,
  `Status` tinyint(4) DEFAULT NULL,
  `Entered_By` smallint(6) DEFAULT NULL,
  `Entered_On` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `trans_guest_booking`
--

INSERT INTO `trans_guest_booking` (`Booking_Id`, `Booking_No`, `First_Name`, `Last_Name`, `Contact_No`, `Room_TId`, `NoOf_Room`, `CheckIn_Date`, `Stay_Duration`, `ExpChkOut_Dt`, `Adult`, `Child`, `Agent_Id`, `Serv_Id`, `Adv_Date`, `Advance_Amt`, `Note`, `Status`, `Entered_By`, `Entered_On`) VALUES
(1, 24102801, 'suman', 'jana', '9733935161', 1, 1, '2024-10-28', 1, '2024-10-29', 2, 1, 1, 1, '2024-10-28', 100, '', 4, 1, '2024-10-28 21:39:54');

-- --------------------------------------------------------

--
-- Table structure for table `trans_guest_ledger`
--

CREATE TABLE `trans_guest_ledger` (
  `Ledger_Id` int(11) NOT NULL,
  `Reservation_Id` int(11) DEFAULT NULL,
  `Trans_Date` date DEFAULT NULL,
  `Narration` varchar(50) DEFAULT NULL,
  `Purpose_Cd` tinyint(4) DEFAULT NULL,
  `Charges_Amount` decimal(10,2) DEFAULT NULL,
  `Collection_Amount` decimal(10,2) DEFAULT NULL,
  `Entered_By` smallint(6) DEFAULT NULL,
  `Entered_On` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `trans_guest_ledger`
--

INSERT INTO `trans_guest_ledger` (`Ledger_Id`, `Reservation_Id`, `Trans_Date`, `Narration`, `Purpose_Cd`, `Charges_Amount`, `Collection_Amount`, `Entered_By`, `Entered_On`) VALUES
(1, 1, '2024-10-06', 'Room Rent Advance', 2, NULL, 1000.00, 1, '2024-10-06 07:41:28'),
(2, 2, '2024-10-06', 'Room Rent Advance', 2, NULL, 1000.00, 1, '2024-10-06 08:33:23'),
(3, 3, '2024-10-08', 'Room Rent Advance', 2, NULL, 1000.00, 1, '2024-10-08 22:19:51'),
(4, 4, '2024-10-28', 'Booking Advance', 1, NULL, 100.00, 1, '2024-10-28 21:41:23'),
(5, 4, '2024-10-28', 'Room Rent Advance', 2, NULL, 1000.00, 1, '2024-10-28 21:41:24'),
(6, 1, '2024-10-28', 'Adhoc Payment', 2, NULL, 100.00, 1, '2024-10-28 21:41:44');

-- --------------------------------------------------------

--
-- Table structure for table `trans_guest_reservation`
--

CREATE TABLE `trans_guest_reservation` (
  `Reservation_Id` int(11) NOT NULL,
  `Booking_Id` int(11) DEFAULT NULL,
  `Reservation_No` int(11) DEFAULT NULL,
  `First_Name` varchar(75) DEFAULT NULL,
  `Last_Name` varchar(75) DEFAULT NULL,
  `Contact_No` varchar(25) DEFAULT NULL,
  `Aadhar_No` varchar(25) DEFAULT NULL,
  `Address_1` varchar(150) DEFAULT NULL,
  `Address_2` varchar(150) DEFAULT NULL,
  `CheckIn_Date` date DEFAULT NULL,
  `Days_No` tinyint(4) DEFAULT NULL,
  `CheckOut_Date` date DEFAULT NULL,
  `Guest_No` tinyint(4) DEFAULT NULL,
  `Adult_No` tinyint(4) DEFAULT NULL,
  `Child_No` tinyint(4) DEFAULT NULL,
  `NoOf_Room` tinyint(4) DEFAULT NULL,
  `Room_Rent` decimal(6,0) DEFAULT NULL,
  `Advance_Amount` decimal(5,0) DEFAULT NULL,
  `Agent_Id` smallint(6) DEFAULT NULL,
  `Special_Request` varchar(200) DEFAULT NULL,
  `Status` tinyint(4) DEFAULT NULL,
  `Entered_By` smallint(6) DEFAULT NULL,
  `Entered_On` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `trans_guest_reservation`
--

INSERT INTO `trans_guest_reservation` (`Reservation_Id`, `Booking_Id`, `Reservation_No`, `First_Name`, `Last_Name`, `Contact_No`, `Aadhar_No`, `Address_1`, `Address_2`, `CheckIn_Date`, `Days_No`, `CheckOut_Date`, `Guest_No`, `Adult_No`, `Child_No`, `NoOf_Room`, `Room_Rent`, `Advance_Amount`, `Agent_Id`, `Special_Request`, `Status`, `Entered_By`, `Entered_On`) VALUES
(1, NULL, 20240001, 'suman', 'jana', '9733935161', '123456789852', 'Kolkata Sodepur', '', '2024-10-06', 2, '2024-10-07', 3, 2, 1, 1, 7500, 1000, 1, '', 2, 1, '2024-10-06 07:41:28'),
(2, NULL, 20240002, 'rajib', 'dam', '9733935161', '123456789852', 'Kolkata Sodepur', '', '2024-10-06', 1, '2024-10-06', 1, 1, 0, 1, 3750, 1000, 1, '', 2, 1, '2024-10-06 08:33:23'),
(3, NULL, 20240003, 'suman', 'jana', '9733935161', '123456789852', 'Kolkata Sodepur', '', '2024-10-08', 1, '2024-10-08', 2, 1, 1, 1, 3750, 1000, 1, 'no', 2, 1, '2024-10-08 22:19:51'),
(4, 1, 20240004, 'suman', 'jana', '9733935161', '123456789852', 'Kolkata Sodepur', '', '2024-10-28', 1, '2024-10-28', 3, 2, 1, 1, 3750, 1100, 1, 'no', 2, 1, '2024-10-28 21:41:23');

-- --------------------------------------------------------

--
-- Table structure for table `trans_guest_room`
--

CREATE TABLE `trans_guest_room` (
  `Allotment_Id` int(11) NOT NULL,
  `Reservation_Id` int(11) DEFAULT NULL,
  `Room_Id` smallint(6) DEFAULT NULL,
  `From_Date` date DEFAULT NULL,
  `Upto_Date` date DEFAULT NULL,
  `Extra_Bed_No` tinyint(4) DEFAULT NULL,
  `Entered_By` smallint(6) DEFAULT NULL,
  `Entered_On` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `trans_guest_room`
--

INSERT INTO `trans_guest_room` (`Allotment_Id`, `Reservation_Id`, `Room_Id`, `From_Date`, `Upto_Date`, `Extra_Bed_No`, `Entered_By`, `Entered_On`) VALUES
(1, 1, 1, '2024-10-06', '2024-10-07', 0, 1, '2024-10-06 07:41:28'),
(2, 2, 2, '2024-10-06', '2024-10-06', 0, 1, '2024-10-06 08:33:23'),
(3, 3, 3, '2024-10-08', '2024-10-08', 0, 1, '2024-10-08 22:19:51'),
(4, 4, 4, '2024-10-28', '2024-10-28', 0, 1, '2024-10-28 21:41:24');

-- --------------------------------------------------------

--
-- Table structure for table `trans_service_ordered`
--

CREATE TABLE `trans_service_ordered` (
  `ServOrd_Id` int(11) NOT NULL,
  `Order_Date` date DEFAULT NULL,
  `Reservation_Id` int(11) DEFAULT NULL,
  `Service_Id` smallint(6) DEFAULT NULL,
  `Charges_Amt` decimal(5,0) DEFAULT NULL,
  `Quantity` tinyint(4) DEFAULT NULL,
  `Payable_Amt` decimal(5,0) DEFAULT NULL,
  `Remarks` varchar(50) DEFAULT NULL,
  `Order_Status` tinyint(4) DEFAULT NULL,
  `Entered_By` smallint(6) DEFAULT NULL,
  `Entered_On` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `trans_service_ordered`
--

INSERT INTO `trans_service_ordered` (`ServOrd_Id`, `Order_Date`, `Reservation_Id`, `Service_Id`, `Charges_Amt`, `Quantity`, `Payable_Amt`, `Remarks`, `Order_Status`, `Entered_By`, `Entered_On`) VALUES
(1, '2024-10-06', 1, 1, 100, 1, 100, '', 1, 1, '2024-10-06 07:43:47'),
(2, '2024-10-28', 1, 1, 100, 1, 100, '', 1, 1, '2024-10-28 21:42:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appl_menus`
--
ALTER TABLE `appl_menus`
  ADD PRIMARY KEY (`Menu_Id`);

--
-- Indexes for table `appl_options`
--
ALTER TABLE `appl_options`
  ADD PRIMARY KEY (`Option_Id`);

--
-- Indexes for table `appl_users`
--
ALTER TABLE `appl_users`
  ADD PRIMARY KEY (`User_Id`);

--
-- Indexes for table `appl_user_group`
--
ALTER TABLE `appl_user_group`
  ADD PRIMARY KEY (`UGrp_Id`);

--
-- Indexes for table `appl_user_grp_permission`
--
ALTER TABLE `appl_user_grp_permission`
  ADD PRIMARY KEY (`Perm_Id`);

--
-- Indexes for table `mst_agent`
--
ALTER TABLE `mst_agent`
  ADD PRIMARY KEY (`Agent_Id`);

--
-- Indexes for table `mst_coupon`
--
ALTER TABLE `mst_coupon`
  ADD PRIMARY KEY (`Coupon_Id`);

--
-- Indexes for table `mst_exp_type`
--
ALTER TABLE `mst_exp_type`
  ADD PRIMARY KEY (`Exp_TId`);

--
-- Indexes for table `mst_menus`
--
ALTER TABLE `mst_menus`
  ADD PRIMARY KEY (`Menu_Id`);

--
-- Indexes for table `mst_menu_category`
--
ALTER TABLE `mst_menu_category`
  ADD PRIMARY KEY (`Category_Id`);

--
-- Indexes for table `mst_paid_services`
--
ALTER TABLE `mst_paid_services`
  ADD PRIMARY KEY (`Service_Id`);

--
-- Indexes for table `mst_room_details`
--
ALTER TABLE `mst_room_details`
  ADD PRIMARY KEY (`Room_Id`);

--
-- Indexes for table `mst_room_rates_regular`
--
ALTER TABLE `mst_room_rates_regular`
  ADD PRIMARY KEY (`Rate_Id_Reg`);

--
-- Indexes for table `mst_room_rates_special`
--
ALTER TABLE `mst_room_rates_special`
  ADD PRIMARY KEY (`Rate_Id_Spl`);

--
-- Indexes for table `mst_room_type`
--
ALTER TABLE `mst_room_type`
  ADD PRIMARY KEY (`Room_TId`);

--
-- Indexes for table `mst_staff`
--
ALTER TABLE `mst_staff`
  ADD PRIMARY KEY (`Staff_Id`);

--
-- Indexes for table `tmp_billfood`
--
ALTER TABLE `tmp_billfood`
  ADD PRIMARY KEY (`SlNo`);

--
-- Indexes for table `tmp_billroom`
--
ALTER TABLE `tmp_billroom`
  ADD PRIMARY KEY (`SlNo`);

--
-- Indexes for table `tmp_billservice`
--
ALTER TABLE `tmp_billservice`
  ADD PRIMARY KEY (`SlNo`);

--
-- Indexes for table `tmp_food`
--
ALTER TABLE `tmp_food`
  ADD PRIMARY KEY (`SlNo`);

--
-- Indexes for table `tmp_room`
--
ALTER TABLE `tmp_room`
  ADD PRIMARY KEY (`SlNo`);

--
-- Indexes for table `trans_agent_ledger`
--
ALTER TABLE `trans_agent_ledger`
  ADD PRIMARY KEY (`Ledger_Id`);

--
-- Indexes for table `trans_checkout_details`
--
ALTER TABLE `trans_checkout_details`
  ADD PRIMARY KEY (`Checkout_Id`);

--
-- Indexes for table `trans_chekout_ledger`
--
ALTER TABLE `trans_chekout_ledger`
  ADD PRIMARY KEY (`Checkout_Id`);

--
-- Indexes for table `trans_expenses`
--
ALTER TABLE `trans_expenses`
  ADD PRIMARY KEY (`Exp_Id`);

--
-- Indexes for table `trans_food_ordered`
--
ALTER TABLE `trans_food_ordered`
  ADD PRIMARY KEY (`FoodOrd_Id`);

--
-- Indexes for table `trans_food_ordered_detail`
--
ALTER TABLE `trans_food_ordered_detail`
  ADD PRIMARY KEY (`Order_Sl`);

--
-- Indexes for table `trans_guest_booking`
--
ALTER TABLE `trans_guest_booking`
  ADD PRIMARY KEY (`Booking_Id`);

--
-- Indexes for table `trans_guest_ledger`
--
ALTER TABLE `trans_guest_ledger`
  ADD PRIMARY KEY (`Ledger_Id`);

--
-- Indexes for table `trans_guest_reservation`
--
ALTER TABLE `trans_guest_reservation`
  ADD PRIMARY KEY (`Reservation_Id`);

--
-- Indexes for table `trans_guest_room`
--
ALTER TABLE `trans_guest_room`
  ADD PRIMARY KEY (`Allotment_Id`);

--
-- Indexes for table `trans_service_ordered`
--
ALTER TABLE `trans_service_ordered`
  ADD PRIMARY KEY (`ServOrd_Id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appl_user_group`
--
ALTER TABLE `appl_user_group`
  MODIFY `UGrp_Id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `appl_user_grp_permission`
--
ALTER TABLE `appl_user_grp_permission`
  MODIFY `Perm_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `mst_agent`
--
ALTER TABLE `mst_agent`
  MODIFY `Agent_Id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `mst_coupon`
--
ALTER TABLE `mst_coupon`
  MODIFY `Coupon_Id` smallint(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mst_exp_type`
--
ALTER TABLE `mst_exp_type`
  MODIFY `Exp_TId` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `mst_menu_category`
--
ALTER TABLE `mst_menu_category`
  MODIFY `Category_Id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `mst_paid_services`
--
ALTER TABLE `mst_paid_services`
  MODIFY `Service_Id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `mst_room_rates_regular`
--
ALTER TABLE `mst_room_rates_regular`
  MODIFY `Rate_Id_Reg` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `mst_room_rates_special`
--
ALTER TABLE `mst_room_rates_special`
  MODIFY `Rate_Id_Spl` smallint(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mst_staff`
--
ALTER TABLE `mst_staff`
  MODIFY `Staff_Id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tmp_billfood`
--
ALTER TABLE `tmp_billfood`
  MODIFY `SlNo` smallint(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tmp_billroom`
--
ALTER TABLE `tmp_billroom`
  MODIFY `SlNo` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tmp_billservice`
--
ALTER TABLE `tmp_billservice`
  MODIFY `SlNo` smallint(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tmp_food`
--
ALTER TABLE `tmp_food`
  MODIFY `SlNo` tinyint(4) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trans_agent_ledger`
--
ALTER TABLE `trans_agent_ledger`
  MODIFY `Ledger_Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trans_chekout_ledger`
--
ALTER TABLE `trans_chekout_ledger`
  MODIFY `Checkout_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `trans_expenses`
--
ALTER TABLE `trans_expenses`
  MODIFY `Exp_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `trans_food_ordered`
--
ALTER TABLE `trans_food_ordered`
  MODIFY `FoodOrd_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `trans_food_ordered_detail`
--
ALTER TABLE `trans_food_ordered_detail`
  MODIFY `Order_Sl` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `trans_guest_ledger`
--
ALTER TABLE `trans_guest_ledger`
  MODIFY `Ledger_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `trans_guest_room`
--
ALTER TABLE `trans_guest_room`
  MODIFY `Allotment_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `trans_service_ordered`
--
ALTER TABLE `trans_service_ordered`
  MODIFY `ServOrd_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
